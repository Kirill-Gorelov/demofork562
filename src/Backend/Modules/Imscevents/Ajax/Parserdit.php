<?php

namespace Backend\Modules\Imscevents\Ajax;

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAJAXAction;
use Backend\Core\Language\Language;
use Backend\Modules\Imscevents\Engine\Model as BackendImsceventsModel;
use Symfony\Component\HttpFoundation\Response;
use Backend\Core\Engine\Model as BackendModel;
use Common\Core\Model as CommonModel;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Парсер объектов инновационной инфраструктуры
 */
class Parserdit extends BackendBaseAJAXAction
{
    private $api_url = 'http://api.mindscan.ru:10088';
    private $login = 'ermakovpv@develop.mos.ru';
    private $password = 'fd54wqaK';

    private $token = '';
    /*
     * Метод удаления дерикторий с файлами
     */
    private static function removeDir($dir): void
    {
        if (is_dir($dir))
        {
            $objects = scandir($dir);

            foreach ($objects as $object)
            {
                if ($object != "." && $object != "..")
                {
                    if (filetype($dir . "/" . $object) == "dir")
                    {
                        self::removeDir($dir . "/" . $object);
                    }
                    else
                    {
                        unlink($dir . "/" . $object);
                    }
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }

    private function setToken()
    {
        if(empty($this->token))
        {
            $url = $this->api_url.'/auth/auth/login';
            $headers = ['Content-Type' => 'application/json'];
            $query = ['userName' => $this->login, 'password' => $this->password];
            $r = \Unirest\Request::post($url, $headers, \Unirest\Request\Body::json($query));
            $this->token = $r->body->jwt;
        }
    }

    private function getPosts()
    {
        $url = $this->api_url.'/data/api/v1/report/getreportresult';
        $headers = ['Content-Type' => 'application/json', 'Authorization' => $this->token];
        $query = [
            'id' => '914a2cfc-9585-4466-aca3-f5504bbd71b2',
            'isCluster' => false,
            'reprint' => false,
            'hideBan' => true,
            'pageNumber' => 0,
            'pageSize' => 50,
            'sortParams' => 'publishtime:Desc'
        ];
        $r = \Unirest\Request::post($url, $headers, \Unirest\Request\Body::json($query));

        echo "<pre>";
        print_r($r);
        echo "</pre>";

    }

    public function execute(): void
    {
        parent::execute();

        set_time_limit(1800);

        $this->setToken();

        $this->getPosts();

        /*
         * Сообщение для AJAX об удачном окончании парсинга
         */
        $this->output(Response::HTTP_OK, null, Language::msg('Done!!!'));

    }

    public static function getRemoteMimeType($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);

        return curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    }


    private function addUpdNews(array $dnpp_item, int $type = 0, int $oii_id = 0){
        if(BackendImscnewsModel::isNewsByDnpp($dnpp_item['id']))
        {
//            $item = [
//                'title' => $dnpp_item['name'],
//                'categories' => serialize([$type]),
//                'short' => $dnpp_item['shortDescription'],
//                'description' => $dnpp_item['description'],
//                'date' => BackendModel::getUTCDate(
//                    null,
//                    strtotime($dnpp_item['newsDate'])
//                ),
//            ];
//
//            BackendImscnewsModel::updateNews($id, $item);
        } else {
            $item = [
                'title' => $dnpp_item['name'],
                'categories' => serialize([$type]),
                'short' => $dnpp_item['shortDescription'],
                'description' => $dnpp_item['description'],
                'date' => BackendModel::getUTCDate(
                    null,
                        strtotime($dnpp_item['newsDate'])
                ),
                'oii_id' => 0,
                'dnpp_id' => $dnpp_item['id'],
                'in_all' => 1,
                'active' => 1,
                'lead' => '',
                'main' => 0
            ];

            $id = BackendImscnewsModel::addNews($item);

            //TODO Сделать нормально
            if(exif_imagetype($dnpp_item['photos'][0]['originalUrl']) == 6) {
                return 0;
            }

            //TODO: Сделать нормально
            $imagePath = FRONTEND_FILES_PATH . '/Imscnews/images/'.$id;

            $image_orig = FRONTEND_FILES_PATH . '/Imscnews/images/dnpp/'.$id;

            self::removeDir($imagePath);
            if($dnpp_item['photos'])
            {
                $filesystem = new Filesystem();
                $filesystem->mkdir([$imagePath . '/128x128', $imagePath . '/512x', $imagePath . '/1024x', $image_orig]);

                copy($dnpp_item['photos'][0]['originalUrl'], $image_orig.'/'.pathinfo($dnpp_item['photos'][0]['originalUrl'])["basename"]);

               CommonModel::generateThumbnails($imagePath, $image_orig.'/'.pathinfo($dnpp_item['photos'][0]['originalUrl'])["basename"]);

                BackendImscnewsModel::updateImage($id, '/src/Frontend/Files/Imscnews/images/dnpp/'.$id.'/'.pathinfo
                    ($dnpp_item['photos'][0]['originalUrl'])["basename"]);
            }
        }

    }
}
