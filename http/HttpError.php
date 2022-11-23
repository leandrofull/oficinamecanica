<?php
	class HttpError {
		public static function error404(): void {
			header("Location: ".DOMAIN."/error404");
		}
	}
?>