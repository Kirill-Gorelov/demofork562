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
		
		// $this->eneremail = $this->get('doctrine')->getRepository(EnerEmail::class)->get($id);
		
		return $this->get('doctrine')->getRepository(EnerEmail::class)->get($id);
	}
	
	
    public function send($id, $datamail)
	{
		if(empty($datamail)){
			return false;
		}
		
		$arr = self::getEmailById($id);
		if(empty($arr) || is_null($arr)){
			return false;
		}

		if(!file_exists('src/Backend/Modules/EnerEmails/Layout/Templates/Email/'.$arr['template'])){
			return false;
		}
		
		// 'template' => $arr['template'], 'subject' => $arr['subject'], 
		$message = Message::newInstance($arr['subject'])
			->setFrom([$arr['efrom'] => $arr['efrom']])
			->setTo([$arr['email'] => $arr['email']])
			// ->setReplyTo([$arr['ecopy'] => $arr['ecopy']])
			->parseHtml(
				'EnerEmails/Layout/Templates/Email/'.$arr['template'],
				['datamail' => $datamail],
				true
			);
			
		// var_export($message);
		FrontendModel::get('mailer')->send($message);
			
		
	}
}