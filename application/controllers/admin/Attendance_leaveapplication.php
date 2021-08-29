<?php

/**
 * Author: Amirul Momenin
 * Desc:Attendance_leaveapplication Controller
 *
 */
class Attendance_leaveapplication extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->load->library('Customlib');
        $this->load->helper(array(
            'cookie',
            'url'
        ));
        $this->load->database();
        $this->load->model('Attendance_leaveapplication_model');
        if (! $this->session->userdata('validated')) {
            redirect('admin/login/index');
        }
    }

    /**
     * Index Page for this controller.
     *
     * @param $start -
     *            Starting of attendance_leaveapplication table's index to get query
     *            
     */
    function index($start = 0)
    {
        $limit = 10;
        $data['attendance_leaveapplication'] = $this->Attendance_leaveapplication_model->get_limit_attendance_leaveapplication($limit, $start);
        // pagination
        $config['base_url'] = site_url('admin/attendance_leaveapplication/index');
        $config['total_rows'] = $this->Attendance_leaveapplication_model->get_count_attendance_leaveapplication();
        $config['per_page'] = 10;
        // Bootstrap 4 Pagination fix
        $config['full_tag_open'] = '<div class="pagging text-center"><nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav></div>';
        $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close'] = '</span></li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close'] = '<span aria-hidden="true"></span></span></li>';
        $config['next_tag_close'] = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close'] = '</span></li>';
        $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close'] = '</span></li>';
        $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close'] = '</span></li>';
        $this->pagination->initialize($config);
        $data['link'] = $this->pagination->create_links();

        $data['_view'] = 'admin/attendance_leaveapplication/index';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Save attendance_leaveapplication
     *
     * @param $id -
     *            primary key to update
     *            
     */
    function save($id = - 1)
    {
        $params = array(
            'subject' => html_escape($this->input->post('subject')),
            'description' => html_escape($this->input->post('description')),
            'date_from' => html_escape($this->input->post('date_from')),
            'time_from' => html_escape($this->input->post('time_from')),
            'end_date' => html_escape($this->input->post('end_date')),
            'end_time' => html_escape($this->input->post('end_time')),
            'total_in_hrs' => html_escape($this->input->post('total_in_hrs')),
            'comments' => html_escape($this->input->post('comments')),
            'status' => html_escape($this->input->post('status')),
            'hr_employee_id' => html_escape($this->input->post('hr_employee_id')),
            'manager_hr_employee_id' => html_escape($this->input->post('manager_hr_employee_id'))
        );

        $data['id'] = $id;
        // update
        if (isset($id) && $id > 0) {
            $data['attendance_leaveapplication'] = $this->Attendance_leaveapplication_model->get_attendance_leaveapplication($id);
            if (isset($_POST) && count($_POST) > 0) {
                $this->Attendance_leaveapplication_model->update_attendance_leaveapplication($id, $params);
                $this->session->set_flashdata('msg', 'Attendance_leaveapplication has been updated successfully');
                redirect('admin/attendance_leaveapplication/index');
            } else {
                $data['_view'] = 'admin/attendance_leaveapplication/form';
                $this->load->view('layouts/admin/body', $data);
            }
        } // save
        else {
            if (isset($_POST) && count($_POST) > 0) {
                $attendance_leaveapplication_id = $this->Attendance_leaveapplication_model->add_attendance_leaveapplication($params);
                $this->session->set_flashdata('msg', 'Attendance_leaveapplication has been saved successfully');
                redirect('admin/attendance_leaveapplication/index');
            } else {
                $data['attendance_leaveapplication'] = $this->Attendance_leaveapplication_model->get_attendance_leaveapplication(0);
                $data['_view'] = 'admin/attendance_leaveapplication/form';
                $this->load->view('layouts/admin/body', $data);
            }
        }
    }

    /**
     * Details attendance_leaveapplication
     *
     * @param $id -
     *            primary key to get record
     *            
     */
    function details($id)
    {
        $data['attendance_leaveapplication'] = $this->Attendance_leaveapplication_model->get_attendance_leaveapplication($id);
        $data['id'] = $id;
        $data['_view'] = 'admin/attendance_leaveapplication/details';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Deleting attendance_leaveapplication
     *
     * @param $id -
     *            primary key to delete record
     *            
     */
    function remove($id)
    {
        $attendance_leaveapplication = $this->Attendance_leaveapplication_model->get_attendance_leaveapplication($id);

        // check if the attendance_leaveapplication exists before trying to delete it
        if (isset($attendance_leaveapplication['id'])) {
            $this->Attendance_leaveapplication_model->delete_attendance_leaveapplication($id);
            $this->session->set_flashdata('msg', 'Attendance_leaveapplication has been deleted successfully');
            redirect('admin/attendance_leaveapplication/index');
        } else
            show_error('The attendance_leaveapplication you are trying to delete does not exist.');
    }

    /**
     * Search attendance_leaveapplication
     *
     * @param $start -
     *            Starting of attendance_leaveapplication table's index to get query
     */
    function search($start = 0)
    {
        if (! empty($this->input->post('key'))) {
            $key = $this->input->post('key');
            $_SESSION['key'] = $key;
        } else {
            $key = $_SESSION['key'];
        }

        $limit = 10;
        $this->db->like('id', $key, 'both');
        $this->db->or_like('subject', $key, 'both');
        $this->db->or_like('description', $key, 'both');
        $this->db->or_like('date_from', $key, 'both');
        $this->db->or_like('time_from', $key, 'both');
        $this->db->or_like('end_date', $key, 'both');
        $this->db->or_like('end_time', $key, 'both');
        $this->db->or_like('total_in_hrs', $key, 'both');
        $this->db->or_like('comments', $key, 'both');
        $this->db->or_like('status', $key, 'both');
        $this->db->or_like('hr_employee_id', $key, 'both');
        $this->db->or_like('manager_hr_employee_id', $key, 'both');

        $this->db->order_by('id', 'desc');

        $this->db->limit($limit, $start);
        $data['attendance_leaveapplication'] = $this->db->get('attendance_leaveapplication')->result_array();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }

        // pagination
        $config['base_url'] = site_url('admin/attendance_leaveapplication/search');
        $this->db->reset_query();
        $this->db->like('id', $key, 'both');
        $this->db->or_like('subject', $key, 'both');
        $this->db->or_like('description', $key, 'both');
        $this->db->or_like('date_from', $key, 'both');
        $this->db->or_like('time_from', $key, 'both');
        $this->db->or_like('end_date', $key, 'both');
        $this->db->or_like('end_time', $key, 'both');
        $this->db->or_like('total_in_hrs', $key, 'both');
        $this->db->or_like('comments', $key, 'both');
        $this->db->or_like('status', $key, 'both');
        $this->db->or_like('hr_employee_id', $key, 'both');
        $this->db->or_like('manager_hr_employee_id', $key, 'both');

        $config['total_rows'] = $this->db->from("attendance_leaveapplication")->count_all_results();
        $db_error = $this->db->error();
        if (! empty($db_error['code'])) {
            echo 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
            exit();
        }
        $config['per_page'] = 10;
        // Bootstrap 4 Pagination fix
        $config['full_tag_open'] = '<div class="pagging text-center"><nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav></div>';
        $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close'] = '</span></li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close'] = '<span aria-hidden="true"></span></span></li>';
        $config['next_tag_close'] = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close'] = '</span></li>';
        $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close'] = '</span></li>';
        $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close'] = '</span></li>';
        $this->pagination->initialize($config);
        $data['link'] = $this->pagination->create_links();

        $data['key'] = $key;
        $data['_view'] = 'admin/attendance_leaveapplication/index';
        $this->load->view('layouts/admin/body', $data);
    }

    /**
     * Export attendance_leaveapplication
     *
     * @param $export_type -
     *            CSV or PDF type
     */
    function export($export_type = 'CSV')
    {
        if ($export_type == 'CSV') {
            // file name
            $filename = 'attendance_leaveapplication_' . date('Ymd') . '.csv';
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/csv; ");
            // get data
            $this->db->order_by('id', 'desc');
            $attendance_leaveapplicationData = $this->Attendance_leaveapplication_model->get_all_attendance_leaveapplication();
            // file creation
            $file = fopen('php://output', 'w');
            $header = array(
                "Id",
                "Subject",
                "Description",
                "Date From",
                "Time From",
                "End Date",
                "End Time",
                "Total In Hrs",
                "Comments",
                "Status",
                "Hr Employee Id",
                "Manager Hr Employee Id"
            );
            fputcsv($file, $header);
            foreach ($attendance_leaveapplicationData as $key => $line) {
                fputcsv($file, $line);
            }
            fclose($file);
            exit();
        } else if ($export_type == 'Pdf') {
            $this->db->order_by('id', 'desc');
            $attendance_leaveapplication = $this->db->get('attendance_leaveapplication')->result_array();
            // get the HTML
            ob_start();
            include (APPPATH . 'views/admin/attendance_leaveapplication/print_template.php');
            $html = ob_get_clean();
            require_once FCPATH . 'vendor/autoload.php';
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->WriteHTML($html);
            $mpdf->Output();
            exit();
        }
    }
}
//End of Attendance_leaveapplication controller