<?php
class CloudFactory{
	private $cloud;
	public function createCloud($cloudName){
		try{
			$fileName = app_path()."/clouds.json";
			$json_string = file_get_contents($fileName);
			$json = json_decode($json_string);
			$clouds = $json->clouds;
			$clouds_lowerCase = array_map("strtolower", $clouds);
			$index = array_search(strtolower($cloudName), $clouds_lowerCase);
			if($index === FALSE)
				throw new UnknownCloudException();
			
			$className = $clouds[$index];
			$reflection = new ReflectionClass($className);

			$classInstance = call_user_func($className.'::getInstance'); 
			
			// $getInstance = $reflection->getMethod('getInstance');
			// $classInstance= $getInstance->invoke();
			// //$classInstance = $ref->newInstance();
			
			if($classInstance instanceof CloudInterface)
				return $classInstance;
			else
				throw new UnknownCloudException();
				

		}catch(ReflectionException $e){
			Log::info("Exception raised in CloudFactory",array('CloudName'=>$cloudName));
 			Log::error($e);
 			throw $e;
		}
	}
}
