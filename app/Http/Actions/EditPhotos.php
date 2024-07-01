<?php

namespace App\Http\Actions;

use Photos;
use Consultations;
use App\Http\Actions\AbstractAction;
use Products;
use LineItems;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 */
class EditPhotos extends AbstractAction
{
    const MSG_SUCCESS = 'Your information has been successfully updated.';

    /**
     *
     */
    public function __invoke(Consultations $consultations, Photos $photos, $id)
    {
        $user   = $this->auth->user();

        if (!$consultation = $consultations->find($id)) {
            return $this->respond(NULL, 404);
        }

        if ($this->request->getMethod() == 'POST') {
            if ($this->request->input('remove')) {
                $deletePhotos = $photos->findById($this->request->input('remove'));
                foreach ($deletePhotos as $photo) {
                    $photos->remove($photo);
                }
            }

            foreach ($this->request->file('photos', array()) as $type_id => $files) {
                $type = $photos->getTypes()->find($type_id);

                foreach ($files as $file) {
                    $photo = $photos->create();

                    $photo->setType($type);
                    $photo->setFile($file);
                    $photo->setConsultation($consultation);
                    $photos->store($photo, TRUE);

                    $consultation->getPhotos()->add($photo);
                }
            }
            return $this->redirect('consultation', 303, ['id' => $consultation->getId()]);
        }

        return $this->render('pages/dashboard/info', 200, get_defined_vars());
    }
}
