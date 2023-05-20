<?php

require_once(TEMPLATE_PATH.'/includes/libraries/PHPExcel/PHPExcel/IOFactory.php');

if(isset($_POST)){

    if(isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'export_contacts')){

        $contacts = tu_get_contact_with_pagination(1, -1);

        $file_name = 'ID-contacts-'.date('d-m-Y');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle($file_name);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', __('STT', TEXT_DOMAIN))
            ->setCellValue('B1', __('Ngày gửi', TEXT_DOMAIN))
            ->setCellValue('C1', __('Mã', TEXT_DOMAIN))
            ->setCellValue('D1', __('Họ tên', TEXT_DOMAIN))
            ->setCellValue('E1', __('SĐT', TEXT_DOMAIN))
            ->setCellValue('F1', __('Dịch vụ quan tâm', TEXT_DOMAIN))
            ->setCellValue('G1', __('Yêu cầu bổ sung', TEXT_DOMAIN));

        $stt = 0;
        $row = 1;

        while($contacts->have_posts()):

            $contacts->the_post();
            $stt++;
            $row++;

            $post_id = get_the_ID();
            $date_time = get_the_date('d/m/Y', $post_id);
            $contact_email = get_post_meta($post_id, 'contact_email', true);
            $contact_name = get_post_meta($post_id, 'contact_name', true);
            $contact_phone = get_post_meta($post_id, 'contact_phone', true);
            $contact_services = get_post_meta($post_id, 'contact_services', true);
            $contact_content = get_post_meta($post_id, 'contact_content', true);


            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $stt)
                ->setCellValue('B' . $row, $date_time)
                ->setCellValue('C' . $row, $post_id)
                ->setCellValue('D' . $row, $contact_name)
                ->setCellValue('E' . $row, $contact_phone)
                ->setCellValue('F' . $row, $contact_services)
                ->setCellValue('G' . $row, $contact_content);

        endwhile;

        ob_end_clean();
        ob_start();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$file_name.'.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $objWriter->save('php://output'); exit;
    }
}
