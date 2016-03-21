<?php

/**
 * Created by PhpStorm.
 * User: Mahedi Azad
 * Date: 19-Mar-16
 * Time: 01:21 PM
 */
class Prize_Sms  extends CI_Model
{
    /**
     * add sms to database by url
     * @return array
     */
    public function set_prize_sms()
    {
        $msdn = trim($this->input->get('msisdn'));
        $msgBody = trim($this->input->get('msg'));


        $insertQuery = "insert into sms_inbox (mobile_no, sms )
                                values ('{$msdn}', '{$msgBody}')";
        $this->db->query($insertQuery);

        $inboxId = $this->db->insert_id();

        $msgArray = $this->msg_format_checking($msgBody);

        if($msgArray['status']){
            $unique_code = $msgArray['unique_code'];
            $keyword = $msgArray['keyword'];

            //checking prize
            $queryCheckPrize = "SELECT * FROM prize where product_number = '{$unique_code}' and status = 'E'";
            $fetchCheckPrize = $this->db->query($queryCheckPrize);

            $queryCheckAlreadyPrized = "SELECT * FROM prize where product_number = '{$unique_code}' and status = 'P'";
            $fetchCheckAlreadyPrized = $this->db->query($queryCheckAlreadyPrized);


            if($fetchCheckPrize->num_rows() > 0){ //have prize
                $prizeResult = $fetchCheckPrize->result_array();

                $prizeId =  $prizeResult[0]['id'];
                $prizeDtl =  $prizeResult[0]['prize_dtl'];

                $insertPrizedSmsQuery = "insert into prized_sms (mobile, sms, prize_id, inbox_id)
                                                values('{$msdn}', '{$msgBody}', '{$prizeId}', '{$inboxId}')";

                $updatePrizeQuery = "update prize set status = 'P' where id = '{$prizeId}' and status = 'E'";

                if(($this->db->query($insertPrizedSmsQuery)) and ($this->db->query($updatePrizeQuery)))
                {
                    $result = array(
                        'msg' => 'Congratulations! You will get your gift within 2 weeks. For details: fb.com/mojomasti',
                        'status' => true
                    );
                }

            }elseif($fetchCheckAlreadyPrized->num_rows() > 0){ //already prized
                $result = array(
                    'msg' => 'Sorry! Your code is already used. For details: fb.com/mojomasti',
                    'status' => true
                );

            }else{ //no prize
                $result = array(
                    'msg' => 'Sorry! You have no gift. Thank you for participate. For details: fb.com/mojomasti',
                    'status' => true
                );
            }


        }else{
            $result = array(
                'msg' => $msgArray['msg'],
                'status' => false
            );
        }



        $result['time'] = date('d-m-Y h:i:s A');
//        echo json_encode($result);
        echo $result['msg'];

        //send sms
        /*if(!empty($msdn))
        {
            $this->send_sms($msdn, $result['msg']);
        }*/

    }

    /**
     * Message format checcking
     * @param $msgBody
     * @return array
     */
    public function msg_format_checking($msgBody)
    {
        if(empty($msgBody)){
            $result = array(
                'msg' => 'Sorry! Invalid unique code/ keyword. Please send correct unique code. Ex:  mojo<space>unique code<space>location send to 16242. For details: fb.com/mojomasti',
                'status' => false
            );

        }else{
            list($keyword,$unique_code) = array_pad(explode(' ', $msgBody, 2), 2, null);

            if(empty($keyword)){
                $result = array(
                    'msg' => 'Sorry! Invalid unique code/ keyword. Please send correct unique code. Ex:  mojo<space>unique code<space>location send to 16242. For details: fb.com/mojomasti',
                    'status' => false
                );

            }elseif(strtoupper($keyword) != 'MOJO'){
                $result = array(
                    'msg' => 'Sorry! Invalid unique code/ keyword. Please send correct unique code. Ex:  mojo<space>unique code<space>location send to 16242. For details: fb.com/mojomasti',
                    'status' => false
                );

            }elseif(empty($unique_code)){
                $result = array(
                    'msg' => 'Sorry! Invalid unique code/ keyword. Please send correct unique code. Ex:  mojo<space>unique code<space>location send to 16242. For details: fb.com/mojomasti',
                    'status' => false
                );

            }else{
                $result = array(
                    'msg' => '',
                    'status' => true,
                    'keyword' => $keyword,
                    'unique_code' => $unique_code
                );
            }
        }
        return $result;
    }




    public function send_sms($msisdn, $msg)
    {
        $url = ''; //set sms api
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
    }

}