<?php
	
	namespace App\Utilities;
	
	use Cocur\Slugify\Slugify;
	use Symfony\Component\HttpFoundation\File\Exception\FileException;
	use Symfony\Component\HttpFoundation\File\UploadedFile;
	
	class GestionPhoto
	{
		private $photo;
		
		public function __construct($photoDirectory)
		{
			
			$this->photo = $photoDirectory;
		}
		
		/**
		 * Enregistrement du fichier dans le repertoire approprié
		 *
		 * @param UploadedFile $file
		 * @param null $media
		 * @return string
		 */
		public function upload(UploadedFile $file, $media = null)
		{
			// Initialisation du slug
			$slugify = new Slugify(); //dd($file);
			
			$originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
			$safeFilename = $slugify->slugify($originalFileName);
			$newFilename = $safeFilename.'-'.Time().'.'.$file->guessExtension(); //dd($this->mediaActivite);
			
			// Deplacement du fichier dans le repertoire dedié
			try {
				if ($media === 'photo') $file->move($this->photo, $newFilename);
				//else $file->move($this->photo, $newFilename);
			}catch (FileException $e){
			
			}
			
			return $newFilename;
		}
		
		/**
		 * Suppression de l'ancien media sur le server
		 *
		 * @param $ancienMedia
		 * @param null $media
		 * @return bool
		 */
		public function removeUpload($ancienMedia, $media = null)
		{
			if ($media === 'photo') unlink($this->photo.'/'.$ancienMedia);
			else return false;
			
			return true;
		}
	}
