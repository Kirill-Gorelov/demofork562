<?
namespace Backend\Modules\EnerEmails\Engine;

use Backend\Modules\EnerEmails\Domain\EnerEmail\EnerEmail;
use Backend\Core\Language\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Common\ModuleExtraType;
use Frontend\Core\Language\Language as FL;
use Symfony\Component\Finder\Finder;
use Common\Mailer\Message;
use Frontend\Core\Engine\Model as FrontendModel;

class Email extends BackendModel
{
	
    private function getEmailById($id)
	{
		if(intval($id) == 0){
			return null;
		}
		
		return self::get('doctrine')->getRepository(EnerEmail::class)->get($id);
	}
	
	// TODO: datamal сделать не пустым
    public function send($id, $datamail = [])
	{
		// TODO: вернуть
		// if(empty($datamail)){
		// 	return false;
		// }
		
		$arr = self::getEmailById($id);
		if(empty($arr) || is_null($arr)){
			return false;
		}

		if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/src/Backend/Modules/EnerEmails/Layout/Templates/Email/'.$arr['template'])){
			return false;
		}
		var_dump($arr,'1234');

		self::parseVariable($arr, $datamail);

		var_dump($arr,'sdfghj');
		
		//TODO: проверить ecopy и email и efrom массивы
		//TODO: проверить не переменная ли email и другие 
		// TODO: а что если будет несколько писем на отпавку, надо проверить
		// $message = Message::newInstance($arr['subject'])
		// 	->setFrom([$arr['efrom'] => $arr['efrom']])
		// 	->setTo([$arr['email'] => $arr['email']])
		// 	// ->setTo([$arr['email'] => [$arr['email'], 'wigoti2258@ettke.com']])
		// 	// ->setReplyTo([$arr['ecopy'] => $arr['ecopy']])
		// 	->parseHtml(
		// 		$_SERVER['DOCUMENT_ROOT'].'/src/Backend/Modules/EnerEmails/Layout/Templates/Email/'.$arr['template'],
		// 		['datamail' => $datamail],
		// 		true
		// 	);
			
		// var_export($message);
		// if(FrontendModel::get('mailer')->send($message)){
		// 	return true;
		// }
		// TODO: письмо не отрпавлено залогировать или еще что-то, сделать уведомление, пока не знаю.
	}
	
	private function parseVariable(&$arr, $datamail)
	{
		var_dump($datamail);
		foreach($arr as $key => &$value){
			var_dump($value);
			//TODO: проходить ли по всем элементам массива
			preg_match_all('/\{(.*?)\}/', $value, $matches); 
			// var_dump($matches);
			if(empty($matches['0'])){ continue; }
			foreach ($matches as $n => $value_valiable) { 
				if ($n == 0) { continue; }
					var_dump($value_valiable);
				// $arr[$key] = str_replace('{'.$value_valiable.'}', $datamail[$value_valiable], $value); 
				foreach ($value_valiable as $value_valiable_t) {
					// var_dump($value_valiable_t);
					if (isset($datamail[$value_valiable_t])) {

						// $arr[$key] = str_replace('{'.$value_valiable_t.'}', $datamail[$value_valiable_t], $value); 
						$value = str_replace('{'.$value_valiable_t.'}', $datamail[$value_valiable_t], $value); 
					}
				}
			} 
		}
	}
}