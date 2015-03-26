if ($do=='changephotofirm') {

    $user_id = cmsCore::request("user_id","int",0);
    if (!$user_id){ cmsCore::halt(); }
    if (($user_id != $inUser->id) && (!$inUser->is_admin)){cmsCore::halt();}
    $usr = $model->getUser($user_id);

    if (cmsCore::inRequest("cd")) {
       if (!cmsUser::checkCsrfToken()) {cmsCore::error404();}

        $errors = false;

        cmsCore::loadClass('upload_photo');
        $inUploadPhoto = cmsUploadPhoto::getInstance();
        // Выставляем конфигурационные параметры
        $inUploadPhoto->only_medium = true;
        $inUploadPhoto->upload_dir    = PATH.'/images/';
        $inUploadPhoto->dir_medium    = 'users/products/';
        $inUploadPhoto->medium_size_w = 242;
        $inUploadPhoto->medium_size_h = 161;
        $inUploadPhoto->is_watermark  = false;
        $inUploadPhoto->input_name    = 'image1';

        $file1 = $inUploadPhoto->uploadPhoto();
        if($file1['filename']) {
            $photo['image1'] = $file1['filename'];
        }

        $inUploadPhoto->filename = '';
        $inUploadPhoto->input_name    = 'image2';
        $file2 = $inUploadPhoto->uploadPhoto();
        if($file2['filename']) {
            $photo['image2'] = $file2['filename'];
        }

        $inUploadPhoto->filename = '';
        $inUploadPhoto->input_name    = 'image3';
        $file3 = $inUploadPhoto->uploadPhoto();
        if($file3['filename']) {
            $photo['image3'] = $file3['filename'];
        }


        if(!$photo){ $errors = true;}

        if (!$errors){

            $inDB->update('cms_user_profiles', $photo, $user_id);

        }

        cmsCore::redirect("/users/".$usr['login']);
   }

    if (!cmsCore::isAjax()){ cmsCore::error404(); }

    cmsPage::initTemplate('components', 'com_users_changephotofirm')->
    assign('usr', $usr)->
    display('com_users_changephotofirm.php');

}