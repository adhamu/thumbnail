<?php

	class Thumbnail {

		public $img;
		private $img_path;
		private $width;
		private $height;
		private $resize_type;
		private $extension;

		public function __construct($img_path, $width, $height, $resize_type) {

			$this->img_path = $img_path;
			$this->resize_type = $resize_type;
			$this->width = (int)$width;
			$this->height = (int)$height;

			$tmpEx = strtolower($img_path);
			$tmpEx = explode(".", $tmpEx);
			$tmpEx = end($tmpEx);
			$this->extension = $tmpEx;

			$this->CreateThumbnail();

		}

		private function CreateThumbnail() {

			$this->img = NULL;

			if($this->extension == "jpg" || $this->extension == "jpeg") {

			    $this->img = @imagecreatefromjpeg($this->img_path)
			        or die("Cannot create new JPEG image");

			} else if($this->extension == "png") {

			    $this->img = @imagecreatefrompng($this->img_path)
			        or die("Cannot create new PNG image");

			} else if($this->extension == "gif") {

			    $this->img = @imagecreatefromgif($this->img_path)
			        or die("Cannot create new GIF image");

			}

			if($this->img) {

				$original_width = imagesx($this->img);
				$original_height = imagesy($this->img);

				switch($this->resize_type) {

					case "scale":

						$scale = min($this->width/$original_height, $this->height/$original_height);

			            $new_width = floor($scale*$original_width);
			            $new_height = floor($scale*$original_height);

			            $temp_img = imagecreatetruecolor($new_width, $new_height);

			            imagecopyresampled($temp_img, $this->img, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

			            imagedestroy($this->img);
			            $this->img = $temp_img;

				        break;

					case "exact":

						$scale = max($this->width/$original_width, $this->height/$original_height);

			            $new_width = floor($scale*$original_width);
			            $new_height = floor($scale*$original_height);

			            $temp_img = imagecreatetruecolor( $new_width, $new_height);
			            $temp_img2 = imagecreatetruecolor($this->width, $this->height);

			            imagecopyresampled($temp_img, $this->img, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

			            if($new_width == $this->width) {

			                $yAxis = ($new_height/2) - ($this->height/2);
			                $yAxisxAxis = 0;

			            } else if ($new_height == $this->height)  {

			                $yAxis = 0;
			                $xAxis = ($new_width/2) - ($this->width/2);

			            }

			            imagecopyresampled($temp_img2, $temp_img, 0, 0, $xAxis, $yAxis, $this->width, $this->height, $this->width, $this->height);

			            imagedestroy($this->img);
			            imagedestroy($temp_img);
			            $this->img = $temp_img2;

						break;

				}
			}

		}

		public function getImg() {
			header("Content-type: image/jpeg");
	    	imagejpeg($this->img, NULL, 100);
		}

		public function getExtension() {
			return $this->extension;
		}

	}

?>