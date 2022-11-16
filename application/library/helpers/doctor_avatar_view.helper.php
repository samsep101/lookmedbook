<?php
    class DoctorAvatarViewHelper {

        public static function view(DoctorModel $doctor, $width, $height)
        {
            if ($doctor->image && file_exists(ABS_ROOT . '/media/upload/'.$doctor->image->folder.$doctor->image->filename))
            {
                return '<img src="'.$doctor->image->cropWithWatermark($width, $height)->path.'" alt="'.$doctor->specialties_names.' '.$doctor->full_name.'" />';
            } else {
                return '<img src="/media/images/no_photo_doctor.jpg" width="66" height="64" alt="'.$doctor->specialties_names.' '.$doctor->full_name.'"/>';
            }
        }

        public static function viewOnCard(DoctorModel $doctor, $width, $height, $url='', $dont_show_link_on_photo = false, $map_card = false, $seo_param = false)
        {
            $html = '';
            if ($doctor->is_virtual == 1 && $map_card)
            {
                $html .= '<img src="/media/images/no_photo_doctor.jpg" width="74" height="111" alt="'.$doctor->specialties_names.' '.$doctor->full_name.'"/>';
                return $html;
            }
            if ($doctor->is_virtual == 1)
            {
               $dont_show_link_on_photo = true;
            }

            if ($doctor && $doctor->card_image && file_exists(ABS_ROOT . '/media/upload/'.$doctor->card_image->folder.$doctor->card_image->filename))
            {
				 $link = (!$dont_show_link_on_photo) ? '<a href="'.DoctorPageLinkViewHelper::getLink($doctor).$url.'">' : '';

                $html = $link;
                if($seo_param)
                    $html .= '<img src="'.$doctor->card_image->cropWithWatermark($width, $height)->path.'" alt="'.$doctor->specialties_names.' '.$doctor->full_name.'" itemprop="image" />';
                else
                    $html .= '<img src="'.$doctor->card_image->cropWithWatermark($width, $height)->path.'" alt="'.$doctor->specialties_names.' '.$doctor->full_name.'"/>';
                $link_end = (!$dont_show_link_on_photo) ? '</a>' : '';
                $html .= $link_end;

                return $html;

            } else {

				$link = (!$dont_show_link_on_photo) ? '<a href="'.DoctorPageLinkViewHelper::getLink($doctor).$url.'">' : '';

                $html = $link;
                $html .= '<img src="/media/images/no_photo_doctor.jpg" width="74" height="111" alt="'.$doctor->specialties_names.' '.$doctor->full_name.'"/>';
                $link_end = (!$dont_show_link_on_photo) ? '</a>' : '';
                $html .= $link_end;

                return $html;
            }
        }

        public static function viewOnPage(DoctorModel $doctor, $width, $height)
        {
            $html = '';

            if (count($doctor->images)){
                $html .= '<ul>';
                $first = 1;
                foreach($doctor->images as $image){
                    $html .= '<li><a data-fancybox-type="iframe" href="/doctor/getDoctorPhotos?doctor_id='.$doctor->getId().'">';

                    $croppedImage = $image->cropWithWatermark($width, $height);

                    if($first)
                    {
                        $first = 0;
                        if ($croppedImage) {
                            $html .= '<img src="'.$image->cropWithWatermark($width, $height)->path.'" alt="'.$doctor->specialties_names.' '.$doctor->full_name.'" itemprop="image" />';
                        } else {
                            $html .= '<img src="/media/images/no_photo_doctor.jpg" width="186" height="241" alt="'.$doctor->specialties_names.' '.$doctor->full_name.'" itemprop="image" />';
                        }
                    }
                    else
                    {
                        $html .= $croppedImage ? '<img src="'.$image->cropWithWatermark($width, $height)->path.'" alt="'.$doctor->specialties_names.' '.$doctor->full_name.'">' : '';
                    }

                    $html .= '</a></li>';
                }

                $html .= '</ul>';
                return $html;
            } else {
                $html .= '<ul>';
                $html .= '<li><img src="/media/images/no_photo_doctor.jpg" width="186" height="241" alt="'.$doctor->specialties_names.' '.$doctor->full_name.'" itemprop="image" /></li>';
                $html .= '</ul>';
                return $html;
            }
        }

        public static function viewOnPageForCarousel(DoctorModel $doctor, $width, $height)
        {
            $html = '';

            if (count($doctor->images)){

                $html .= '<div class="navigation">';
                    $html .= '<a href="javascript:void(0)" class="prev prev-navigation"></a>';
                    $html .= '<a href="javascript:void(0)" class="next next-navigation"></a>';
                    $html .= '<div class="carousel carousel-navigation">';
                        $html .= '<ul>';
                        foreach($doctor->images as $image) {
                            $croppedImage = $image->crop($width, $height);
                            if ($croppedImage) {
                                $html .= '<li><img src="'. $croppedImage->path.'" alt="'.$doctor->specialties_names.' '.$doctor->full_name.'"></li>';
                            }
                        }
                        $html .= '</ul>';
                    $html .='</div>';
                $html .='</div>';

                return $html;
            } else {
                return $html;
            }
        }
    }