<?
namespace Prokhorov\Trafic;

use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;
Loader::IncludeModule('file');
use Bitrix\Highloadblock as HL;



class Import {

	private $idFiles;
	private $pathFiles;
	private $contentFiles;

	public  function  __construct($file){

		try 
		{
			$this->checkFormat($file);	
			$this->idFiles = $this->saveFile($file);
			$this->pathFiles = $this->getPath($this->idFiles);
			$this->contentFiles = $this->getContents($this->pathFiles);
		} 
		catch (Exception $e) {
            echo 'Ошибка: ' . $e->getMessage();
            echo 'Код ошибки: ' . $e->getCode();
		}

	}

	public function getMascList($params){

		try 
		{
			$data = $this->contentFiles;
			$masks = $this->validateMaskFile($data);

		} 
		catch (Exception $e) {
            echo 'Ошибка: ' . $e->getMessage();
            echo 'Код ошибки: ' . $e->getCode();
		}

		$maskList = [];

        foreach ($masks as $line) {

        	if($params)
			{
	        	if (strpos($line, $params) === 0) 
	        	{
	            	continue; // Переход к следующей итерации цикла
	        	}
			}

	        // Удаляем лишние пробелы
	        $line = trim($line);
	        // Проверяем, что строка не пустая
	        if (!empty($line)) 
	        {
	            $parts = explode('/', $line);

		        $ip = $parts[0];
		        $mask = $parts[1];

		        if($this->validateIPandMasc($ip,$mask))
		        {
		        	
			        $maskList[] = [
				        "IP" => $ip,
				        "MASKA" => $mask
			        ];
		        }   
	        }

	        
        }

        return $maskList;

	}

	private function validateMaskFile($file){


		if(!$file)
		{
			throw new SystemException("Не передано содержимое файла");
		}

		

		$lines = explode("\n", $file);

		if(!is_array($lines))
		{
			throw new SystemException("Ошибка при формировании массива из файла");
		}

		return $lines;
	}

	private function validateIPandMasc($ip,$mask){
		if (!preg_match('/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', $ip) || !preg_match('/^\d{1,2}$/', $mask)) {
            return false;
        }

        return true;
	}

	private function checkFormat($file){

		$name = substr($file["name"], -4, 4);

		if($name != ".txt")
		{
			throw new SystemException("Неверный формат файла");
		}

	}

	private function saveFile($file){
		$id = \CFile::SaveFile($file, "errorform");

		if(!$id>0)
		{
			throw new SystemException("Ошибка при сохранении файла");
		}

		return $id;
	}

	private function getPath($id){

		$pathFile = \CFile::GetPath($id);

		if(empty($pathFile))
		{
			throw new SystemException("Ошибка при получении пути файла");
		}
		return $pathFile;
	}

	private function getContents($path){

        $fileContent = file_get_contents($_SERVER['DOCUMENT_ROOT'].$path);
        return $fileContent;
	}
}



?>