<?php
    class ClinicAvatarViewHelper
    {
        public static function view(ClinicModel $clinic, $width, $height)
        {
           	return self::getImageView($clinic, $clinic->card_image, $width, $height);
        }

		public static function getImageView(ClinicModel $clinic, ImageModel $image = null, $width = null, $height = null)
		{
			if ($image) {
				return '<img src="/media/images/no_clinic_small.gif" alt="' . $clinic->full_name . '" />';
			} else {
				return '<img src="/media/images/no_clinic_small.gif" width="' . $width . '" height="' . $height . '" alt="' . $clinic->full_name . '"/>';
			}
		}

        public static function viewOnCard(ClinicModel $clinic, $width, $height)
        {
            $html = '';

            if ($clinic->card_image) {
                $croppedImage = $clinic->card_image->crop($width, $height);
                if ($croppedImage) {
                    $html = '<a href="'.ClinicPageLinkViewHelper::getLink($clinic).'">';
                    $html .= '<img src="' . $croppedImage->path . '" alt="' . $clinic->name . '"/>';
                    $html .= '</a>';
                }
            } else {
                $html = '<a href="'.ClinicPageLinkViewHelper::getLink($clinic).'">';
                $html .= '<img src="/media/images/no_clinic_small.gif" width="74" height="31" alt="' . $clinic->name . '"/>';
                $html .= '</a>';
            }

            return $html;
        }

        public static function viewOnPage(ClinicModel $clinic, $width, $height)
        {
            $html = '';

            if ($clinic->image) {

                $html .= '<ul>';
                $html .= '<li><img src="' . $clinic->image->cropWithWatermark($width, $height)->path . '" alt="' . $clinic->name . '" /></li>';

                if ($clinic->images) {
                    foreach ($clinic->images as $image) {
                        $html .= '<li><a data-fancybox-type="iframe" href="/doctor/getDoctorPhotos?doctor_id=' . $clinic->getId() . '">';
                        $html .= '<img src="' . $image->cropWithWatermark($width, $height)->path . '" alt="' . $clinic->name . '">';
                        $html .= '</a></li>';
                    }
                }
                $html .= '</ul>';
                return $html;

            } else {
                $html .= '<ul>';
                $html .= '<li><img src="/media/images/no-photo2.gif" alt="' . $clinic->name . '"/></li>';
                $html .= '</ul>';
                return $html;
            }
        }

        public static function viewOnPageForCarousel(ClinicModel $clinic, $width, $height)
        {
            $html = '';

            if ($clinic->image) {

                $html .= '<div class="navigation">';
                $html .= '<a href="javascript:void(0)" class="prev prev-navigation"></a>';
                $html .= '<a href="javascript:void(0)" class="next next-navigation"></a>';
                $html .= '<div class="carousel carousel-navigation">';
                $html .= '<ul>';
                $html .= '<li><img src="' . $clinic->image->cropWithWatermark($width, $height)->path . '" alt="' . $clinic->name . '" /></li>';

                if ($clinic->images) {
                    foreach ($clinic->images as $image) {
                        $html .= '<li><img src="' . $image->cropWithWatermark($width, $height)->path . '" alt=' . $clinic->name . '"></li>';
                    }
                }
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            } else {
                return $html;
            }
        }
    }