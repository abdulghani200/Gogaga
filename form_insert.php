<?php
include "config.php";

function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        
        return $data;
      }     
if($_SERVER["REQUEST_METHOD"] == "POST")
  {
    
  if(isset($_POST["submit"]))
  {
      // Partner Details
      $form_filled_by =  test_input($_POST["first_1"]);
      $holi_partner_name =  test_input($_POST["first_2"]);
      $holi_partner_loc = test_input($_POST["first_3"]);
      $sales_partner_name =  test_input($_POST["first_4"]);
      $sales_partner_loc =  test_input($_POST["first_5"]);
      //Customer Details
      $cust_firstname =  test_input($_POST["second_1"]);
      $cust_lastname =  test_input($_POST["second_2"]);
      $contact_phone = $_POST["second_3"];
      $preferred_time = test_input($_POST["second_4"]);
      $cust_addr =  test_input($_POST["second_5"]);
      $cust_email =  $_POST["second_6"];
     
      //Holiday Details
      $trip_start_loc = test_input($_POST["third_1"]);
      $holi_dest = test_input($_POST["third_2"]);
      $holi_type = test_input($_POST["third_3"]);

      
      $date_of_travel = test_input($_POST["third_4"]);
      $mon_of_travel = test_input($_POST["third_5"]);
      $year_of_travel = test_input($_POST["third_6"]);
      
      $return_date_of_travel = test_input($_POST["third_7"]);
      $return_mon_of_travel = test_input($_POST["third_8"]);
      $return_year_of_travel = test_input($_POST["third_9"]);
      
      $duration = $_POST["trip_dur"];      
      

      $date_of_travel =$date_of_travel."-".$mon_of_travel."-".$year_of_travel;
      $return_date_of_travel =$return_date_of_travel."-".$return_mon_of_travel."-".$return_year_of_travel;

      $no_of_adults = test_input($_POST["third_10"]);
      $no_of_childs = test_input($_POST["third_11"]);
      $no_of_infants = test_input($_POST["third_12"]);

   
      $totalPersonsCount = (int)$no_of_adults+(int)$no_of_childs+(int)$no_of_infants;

      $childage = $_POST["childage"];

      //Mode of Travel
      $travel_mode =  test_input($_POST["fourth_1"]);
      $travel_from =  test_input($_POST["fourth_2"]);
      $travel_to =  test_input($_POST["fourth_3"]);
      //Accomodation Details
      $type_hotel =  test_input($_POST["fifth_1"]);
      $acc_type =  test_input($_POST["fifth_2"]);
      $no_rooms =  test_input($_POST["fifth_3"]);
      $additional_details =  test_input($_POST["fifth_4"]);
      $food_pref = test_input( $_POST["fifth_5"]);
      $specific_food_pref =  test_input($_POST["fifth_6"]);
      $sight_pref =  test_input($_POST["fifth_7"]);
      $budget =  test_input($_POST["fifth_8"]);
      $lead_status =  test_input($_POST["fifth_9"]);
      $datesent = date("Y-m-d");
      $ghrnno =$_POST["pagecontrol"];

      $sno = 0;

           if($ghrnno == "newform") 
          {
              

                 // echo "<br> $sno ,$ghrnno";

              $sql = "INSERT INTO agent_form_data (form_filled_by,holi_partner_name,holi_partner_loc,sales_partner_name,
                sales_partner_loc,cust_firstname,cust_lastname,
                contact_phone,preferred_time,cust_addr,cust_email,trip_start_loc,holi_dest,
                holi_type,date_of_travel,return_date_of_travel,duration,no_of_adults,no_of_childs,
                no_of_infants,child_ages,travel_mode,travel_from,travel_to,type_hotel,acc_type,
                no_rooms,additional_details,food_pref,specific_food_pref,
                sight_pref,budget,lead_status,datesent) 
                VALUES (
                        '$form_filled_by','$holi_partner_name','$holi_partner_loc','$sales_partner_name',
                '$sales_partner_loc','$cust_firstname','$cust_lastname','$contact_phone','$preferred_time','$cust_addr','$cust_email','$trip_start_loc','$holi_dest',
                '$holi_type','$date_of_travel','$return_date_of_travel','$duration','$no_of_adults','$no_of_childs',
                '$no_of_infants','$childage','$travel_mode','$travel_from','$travel_to','$type_hotel','$acc_type',
                '$no_rooms','$additional_details','$food_pref','$specific_food_pref',
                '$sight_pref','$budget','$lead_status','$datesent')";


            if(($conn->query($sql))== true)
                     {

                      // CODE FOR INSERTING INTO ITINERARY TABLES

                      $sql= "SELECT MAX(ref_num) AS max_no 
                      FROM agent_form_data
                      WHERE contact_phone = '$contact_phone'
                      ";
                    $res = $conn->query($sql);
                     if ($res->num_rows) 
                      {
                        if($row = $res->fetch_assoc()) 
                        {
                              $ref_num = $row["max_no"];
                        }
                      }



                      $sno = '1';
                   if($holi_type =="Domestic")
                    {
                        $sql="INSERT INTO itinerary_domestic (ghrnno) VALUES('$ref_num')
                        ";
                         if(($conn->query($sql))== true)
                            {                       
                                  //echo "Domestic done<br>";

                                 $sql = "INSERT INTO hotels_domestic (ghrno,sno) 
                                      VALUES ('$ref_num',$sno)";
                                 if(($conn->query($sql))== true)
                                  {
                                    // Done
                                  }


                            }
                           


                    }
                    elseif($holi_type =="International")
                    {
                       
                        $sql="INSERT INTO itinerary_inter (ghrno) VALUES('$ref_num')
                        ";
                         if(($conn->query($sql))== true)
                            {                       
                                  //echo "Domestic done<br>";


                              $sql = "INSERT INTO hotels_inter (ghrnno,sno) 
                                      VALUES ('$ref_num',$sno)";
                               if(($conn->query($sql))== true)
                                {
                                  // Done
                                }

                            }
                           


                    }
                    else
                    {

                    }


                    $sql = "INSERT INTO flights_info (ghrno,sno,airtrav) 
                            VALUES ('$ref_num',$sno,'$totalPersonsCount')";
                        if(($conn->query($sql))== true)
                        {                       
                             // echo "Flights  Done<br>";
                        }
                        else{}



                    $sql = "INSERT INTO designdetails (ghrno) 
                            VALUES ('$ref_num')";
                        if(($conn->query($sql))== true)
                        {                       
                             // echo "Flights  Done<br>";
                        }
                        else{}












                     $sql= "UPDATE login 
                            SET notif_count =notif_count + 1 
                            WHERE handle_type IN ('$holi_type','Both')
                            ";
                     if($conn->query($sql))
                          header("Location:form_status.php?status=1");
                      else
                        header("Location:form_status.php?status=0");




                      }
                  else
                       header("Location:form_status.php?status=0");
                        
                   $conn->close();

            // The below operation is for Creating an empty row of particular


                   



















          }
          else
          {

              $sql ="UPDATE  agent_form_data  
              SET form_filled_by = '".$form_filled_by."', holi_partner_name = '".$holi_partner_name."', 
              holi_partner_loc = '".$holi_partner_loc."', sales_partner_name = '".$sales_partner_name."', sales_partner_loc = '".$sales_partner_loc."', cust_firstname = '".$cust_firstname."',
               cust_lastname = '".$cust_lastname."', contact_phone = '".$contact_phone."', preferred_time = '".$preferred_time."', cust_addr = '".$cust_addr."', cust_email = '".$cust_email."',
                trip_start_loc = '".$trip_start_loc."', holi_dest = '".$holi_dest."', holi_type = '".$holi_type."', date_of_travel = '".$date_of_travel."', return_date_of_travel = '".$return_date_of_travel."',
                 duration = '".$duration."', no_of_adults = '".$no_of_adults."', no_of_childs = '".$no_of_childs."', no_of_infants = '".$no_of_infants."', child_ages = '".$childage."',
                  travel_mode = '".$travel_mode."', travel_from = '".$travel_from."', travel_to = '".$travel_to."', type_hotel = '".$type_hotel."', acc_type = '".$acc_type."', 
                  no_rooms = '".$no_rooms."', 
                  additional_details = '".$additional_details."', food_pref = '".$food_pref."', specific_food_pref = '".$specific_food_pref."', sight_pref = '".$sight_pref."', budget = '".$budget."', 
                  lead_status = '".$lead_status."' 
                  WHERE ref_num = '".$ghrnno."'


                    ";

                     if(($conn->query($sql))== true)
                     {
                        header('location:view_itinerary.php?q='.$ghrnno);
                     }
                     else
                     {
                        header('location:view_itinerasdsaary.php?q='.$ghrnno);
                     }



          }    
       
         
























        }
      }
?>