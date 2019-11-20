<?php

namespace Backend\Modules\Imscevents\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Imscevents\Engine\Model as BackendImsceventsModel;
use Backend\Modules\Imscevents\Form\ImsceventsDelType;

/**
 * Контроллер удаления мероприятия
 */
class Delevent extends BackendBaseActionDelete
{
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

    public function execute(): void
    {
        /*
         * Создаём форму для подтверждения удаления
         */
        $deleteForm = $this->createForm(
            ImsceventsDelType::class,
            null,
            ['module' => $this->getModule()]
        );

        /*
         * Проверка на валидносность
         */
        $deleteForm->handleRequest($this->getRequest());
        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            $this->redirect(BackendModel::createUrlForAction('Index', null, null, ['error' => 'something-went-wrong']));

            return;
        }
        $deleteFormData = $deleteForm->getData();

        /*
         * id удаляемого мероприятия
         */
        $this->id = (int) $deleteFormData['id'];

        /*
         * Проверка мероприятия на наличие
         */
        if ($this->id === 0 || !BackendImsceventsModel::existsEvent($this->id)) {
            $this->redirect(BackendModel::createUrlForAction('Index', null, null, ['error' => 'non-existing']));

            return;
        }

        parent::execute();

        /*
         * Удаляем мероприятие
         */
        BackendImsceventsModel::deleteEvent($this->id);

        /*
         * Удаляем изображения мероприятия
         */
        $imagePath = FRONTEND_FILES_PATH . '/Imscevents/images/'.$this->id;
        self::removeDir($imagePath);

        /*
         * Редирект на список мероприятий
         */
        $this->redirect(BackendModel::createUrlForAction(
            'Index',
            null,
            null
        ));

    }
}
