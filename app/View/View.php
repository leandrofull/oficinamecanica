<?php
	namespace app\View;

	class View {
		private string $pageTitle = 'Sem Título';
		private string $cssFiles = '';
		private string $jsFiles = '';
		private string $navLinkActive = '';

		public function setPageTitle(string $newPageTitle): void {
			$this->pageTitle = $newPageTitle;
		}

		public function getPageTitle(): string {
			return $this->pageTitle;
		}

		public function addCSSFile(string $newCSSFile): void {
			$this->cssFiles .= '<link rel="stylesheet" type="text/css" href="'.DOMAIN.'/CSS/'.$newCSSFile.'">';
		}

		public function getCSSFiles(): string {
			return $this->cssFiles;
		}

		public function addJSFile(string $newJSFile): void {
			$this->jsFiles .= '<script type="text/javascript" src="'.DOMAIN.'/JS/'.$newJSFile.'"></script>';
		}

		public function getJSFiles(): string {
			return $this->jsFiles;
		}

		public function setNavLinkActive(string $navLinkActive): void {
			$this->navLinkActive = $navLinkActive;
		}

		public function getNavLinkActive(): string {
			return $this->navLinkActive;
		}
	}
?>