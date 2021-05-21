<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Image;
use App\Comment;
use App\Like;

class ImageController extends Controller
{

	//Acceso restringido//Solo para usuarios
	public function __construct(){
        $this->middleware('auth');
    }
	
	//La vista para subir las imagenes
	public function create(){
		return view('image.create');
	}
	
	//Funcion para guardar en la base de datos las imagenes
	public function save(Request $request){
		
		//Validación
		$validate = $this->validate($request, [
			'description' => 'required',
			'image_path'  => 'required|image'
		]);
		
		// Recoger datos
		$image_path = $request->file('image_path');
		$description = $request->input('description');
		
		// Asignar valores nuevo objeto
		$user = \Auth::user();
		$image = new Image();
		$image->user_id = $user->id;
		$image->description = $description;
		
		// Subir fichero
		if($image_path){
			//Sacamos el nombre del fichero original
			$image_path_name = time().$image_path->getClientOriginalName();

			
			Storage::disk('images')->put($image_path_name, File::get($image_path));
			$image->image_path = $image_path_name;
		}
		
		//Insetamos en la BD
		$image->save();
		
		//Mensjae flash para decir que se ha subido la foto
		return redirect()->route('home')->with([
			'message' => 'La foto ha sido subida correctamente!!'
		]);
	}
	
	//Funcion para mostrar las imagenes en el inicio
	public function getImage($filename){
		$file = Storage::disk('images')->get($filename);
		return new Response($file, 200);
	}
	
	public function detail($id){
		$image = Image::find($id);
		
		return view('image.detail',[
			'image' => $image
		]);
	}
	
	//Funcion para eliminar las imagenes
	public function delete($id){
		

		$user = \Auth::user();
		//Sacamos el id de la imagen que queremos borrar
		$image = Image::find($id);

		//Sacar los comentario asociados a la imagen
		$comments = Comment::where('image_id', $id)->get();

		//Sacar los likes asociados a la imagen
		$likes = Like::where('image_id', $id)->get();
		
		//Condicion que permite borrar cosas solo si eres el propietario
		//Si el  id del usuario que ha creado la imagen y el usuario que esta logeado es el mismo borrar
		if($user && $image && $image->user->id == $user->id){
			
			// Eliminar comentarios
			//Si cuento los comentarios tengo mas de 1 o mayor se recorre y en cada iteracion lo borra.
			if($comments && count($comments) >= 1){
				foreach($comments as $comment){
					$comment->delete();
				}
			}
			
			// Eliminar los likes
			//Si cuento los likes tengo mas de 1 o mayor se recorre y en cada iteracion lo borra.

			if($likes && count($likes) >= 1){
				foreach($likes as $like){
					$like->delete();
				}
			}
			
			// Eliminar ficheros de imagen
			//Acceder al strorage para borrar las imagenes asociadas
			Storage::disk('images')->delete($image->image_path);
			
			// Eliminar registro imagen
			$image->delete();
			
			//Mensaje borrado
			$message = array('message' => 'La imagen se ha borrado correctamente.');
		}else{
			$message = array('message' => 'La imagen no se ha borrado.');
		}
		
		//Redireccion al inicio con una sesion flash
		return redirect()->route('home')->with($message);
	}
	
	//Funcion para editar las imagenes
	public function edit($id){
		//Sacar usuario identificado
		$user = \Auth::user();

		//Sacamos el objeto imagen que queremos sacar
		$image = Image::find($id);
		
		//Si existe uiser y existe imagen y ademas si imagen y user, id son iguales al dueño de la publicacion
		if($user && $image && $image->user->id == $user->id){
			return view('image.edit', [
				'image' => $image
			]);
		}else{
			//Redireccion  a home
			return redirect()->route('home');
		}
	}
	
	//Funcion para actualizar la imagen
	public function update(Request $request){
		//Validación
		$validate = $this->validate($request, [
			'description' => 'required',
			'image_path'  => 'image'
		]);
		
		// Recoger datos
		$image_id = $request->input('image_id');
		$image_path = $request->file('image_path');
		$description = $request->input('description');
		
		// Conseguir objeto image
		$image = Image::find($image_id);
		$image->description = $description;
		
		// Subir fichero
		if($image_path){
			$image_path_name = time().$image_path->getClientOriginalName();
			Storage::disk('images')->put($image_path_name, File::get($image_path));
			$image->image_path = $image_path_name;
		}
		
		// Actualizar registro
		$image->update();
		
		//Redireccion a la ruta del detalle de la imagen
		return redirect()->route('image.detail', ['id' => $image_id])
						//Mensaje con sesion flash
						 ->with(['message' => 'Imagen actualizada con exito']);
	}
	
}
