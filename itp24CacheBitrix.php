<?php
	class itp24CacheBitrix{
		
		private static $obCache = false;
		
		private static $config = Array(
			'ttl'=>60*60*24*7,
			'id'=>false,
			'dir'=>'/',
			'language'=>'en'
		);
		
		private static $lang = Array(
			'all'=>Array(
				'nbsp'=>'&nbsp;'
			),
			'en'=>Array(
				'noId'=>'Caching id not set',
				'noInt'=>'The cache lifetime should be a numeric value in seconds',
				'cacheError'=>'The parameter must be a callback function with a return value in the form of an array for caching',
				'generated'=>'Generated',
				'cached'=>'Cached result'
			)
		);
		
		private static function check($t,$v){
			if($t == 'int')
				if(intVal($v))
					throw new Exception(self::$lang[self::config['language']]['noInt']);
		}
		
		public static function param($i=false, $t = false, $d = false){
			if(class_exists('CPHPCache')):
				if(!self::$obCache) self::$obCache = new CPHPCache;
				
				if($i) self::$config['id'] = $i; else throw new Exception(self::$lang[self::config['language']]['noId']);
				
				if($t):
					self::check($t);
					
					self::$config['ttl'] = $t;
				endif;
				
				if($d) self::$config['dir'] = $d;
			endif;
		}
		
		public static function cache($f=false,$c = false,$s = false, $r = false, $s = false){
			if(self::$config['id']):
				if(is_callable($f)):
					if($c):
						self::$obCache->CleanDir(self::$config['id'].self::$config['dir']);
					endif;
					
					if(!$c&&self::$obCache->InitCache(self::$config['ttl'], self::$config['id'], self::$config['dir'])):
						if($s):
							print self::$lang[self::config['language']]['cached'];
						endif;
						
						return self::$obCache->GetVars();
					else:
						if($s):
							print self::$lang[self::config['language']]['generated'];
						endif;
						
						$r = $f();
						if(is_array($r)&&sizeOf($r)):
							$s = true;
						endif;
						
						if($s):
							self::$obCache->EndDataCache($r);
							return $r;
						endif;
					endif;
				endif;
				
				if(!$s):
					throw new Exception(self::$lang[self::config['language']]['cacheError']); 
				endif;
			else:
				throw new Exception(self::$lang[self::config['language']]['noId']);
			endif;
		}
		
	}
?>
