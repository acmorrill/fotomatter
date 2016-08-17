<?php

class PhotoSellablePrint extends AppModel {

    public $name = 'PhotoSellablePrint';
    public $belongsTo = array(
        'Photo',
        'PhotoAvailSizesPhotoPrintType'
    );

    public function delete_all_by_photo_id($photo_id) {
        if (!$this->deleteAll(array(
            'PhotoSellablePrint.photo_id' => $photo_id
        ), false, false)) {
            $this->major_error('Failed to delete sellable prints for photo id');
        }
    }
}
