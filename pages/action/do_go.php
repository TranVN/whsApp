<?php 
    
    
   try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password,$options);
    
    $sql = "SELECT info_cus.id_cus,info_cus.name_cus, info_cus.phone_cus,info_cus.add_cus, info_cus.des_cus,info_cus.yc_book, info_cus.note_book,
             info_worker.name_worker,info_cus.date_book, work_do.id_work, nv_phan FROM work_do,info_worker,info_cus 
             WHERE  info_cus.date_book like '%$timelive%'and work_do.id_worker = info_worker.id_worker and work_do.id_cus = info_cus.id_cus and info_cus.kind_book like '%Gỗ%'
             and info_cus.flag_book = '1' and  work_do.sum_thu = '0' ORDER BY info_worker.name_worker ASC ";
             
    $q = $pdo->query($sql);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    if(empty($q))
    {
        $sql = "SELECT info_cus.id_cus,info_cus.name_cus, info_cus.phone_cus,info_cus.add_cus, info_cus.des_cus,info_cus.yc_book, info_cus.note_book,
             info_worker.name_worker,info_cus.date_book, work_do.id_work,nv_phan FROM work_do,info_worker,info_cus 
             WHERE  work_do.sum_thu = 0 and work_do.id_worker = info_worker.id_worker and work_do.id_cus = info_cus.id_cus and info_cus.kind_book like '%Gỗ%'
             and info_cus.flag_book = '1' order by info_worker.name_worker ASC";
             
    $q = $pdo->query($sql);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    }
    $sql3 = "SELECT id_cus,name_cus, phone_cus, add_cus, des_cus, yc_book, note_book, kind_book, date_book , nv_add
     FROM info_cus where kind_book like '%go%' and  flag_book = '0' and date_book like '%$time_search%' and flag_status is NULL order by des_cus DESC ";
    $q3 = $conn->query($sql3);
    $q3->setFetchMode(PDO::FETCH_ASSOC);
    $tho= $conn->prepare("select * FROM info_worker where status_worker = 0 and today_off = 0  order by name_worker ASC ");
        $tho->setFetchMode(PDO::FETCH_ASSOC); // set kiểu mảng cho giá trị trả về
        $tho->execute();
        $rs = $tho->fetchAll();
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
echo "
<h3> Lịch Chờ</h3>
  <table class='table table-bordered table-hover'>
  <thead>
          
              <tr>
              <th class='col-md-2'>Thông Tin</th>
              <th class='col-md-1'>Tên KH</th>
              
              <th class='col-md-2'>Địa Chỉ</th>
              <th class='col-md-1'>Quận</th>
              <th class='col-md-1'>Số Điện Thoại</th>
              
              <th class='col-md-1' >Ghi chú</th>
              
              <th class='col-md-2'>Thao Tác</th>
              </tr>

          </thead>

       <tbody>";
           while ($row3 = $q3->fetch()):
                    echo "
                    <tr>
                    <th >".htmlspecialchars($row3['yc_book'])."</th>
                    <td >".htmlspecialchars($row3['name_cus'])."</td>
                    
                    <td >".htmlspecialchars($row3['add_cus'])."</td>
                    <td >".htmlspecialchars($row3['des_cus'])."</td>
                    <td >".htmlspecialchars($row3['phone_cus'])."</td>
                    
                    <td>".htmlspecialchars($row3['note_book'])."</td>
                    
                                             
                    <td>
                    <button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#phantho".$row3['id_cus']."'>Phân</button>

                      <!-- Modal -->
                      <div id='phantho".$row3['id_cus']."' class='modal fade' role='dialog'>
                          <div class='row'>
                          <div class='modal-dialog'>

                          <!-- Modal content-->
                          <div class='modal-content'>
                          <div class='modal-header'>
                              <button type='button' class='close' data-dismiss='modal'>&times;</button>
                              <h4 class='modal-title'>Chọn Thợ Bạn Muốn phân</h4>
                          </div>
                          <div class='modal-body'>
                          <form action='includes/logic/XL_phan_lich.php' method='POST' class ='hop'>
                              <label>Chọn Thợ cần Phân :</label>
                              <input type ='hidden' name='ac' value ='phantho'/>
                              <input type ='hidden' name='ki' value ='3' />
                              <input type ='hidden' name='nv' value='".$ruser['real_name']."'/>
                              <input type='hidden' name = 'id_cus' value='".$row3['id_cus']."'/>
                              <input type='hidden' name = 'note' value='".$row3['note_book']."'/>
                              <select name='name_worker'>";
                              foreach ($rs as $row1) {
                                  echo '<option>';
                                  
                                  echo $row1['name_worker'] . ' ';
                                  echo $row1['add_worker'] . ' ';
                                  echo $row1['id_worker']." ";
                                  echo '</option>';
                                 
                                  }  
                              
                              
                              echo "</select>
                              <br>
                              
                              <label>Chọn Thợ phụ nếu cần  :</label>
                              <textarea style='width: 100%; hight:120px;' name='phu'></textarea>
                             
                          
                          </div>
                          <div class='modal-footer'>
                          <input type='submit' value='Xác Nhận' class='btn btn-success'/>   <button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>
                          </div>
                          </form>
                          </div>

                      </div>
                          </div>
                      
                      </div>
                 
                    
                            
                            &nbsp";
                            echo "<a href ='".BASE_URL."includes/logic/suaKH.php?id_cus=".$row3['id_cus']."&action=sua&nv=".$ruser['real_name']." 'class='btn btn-sm btn-success'> Sửa</a>";
                            echo "&nbsp";
                            echo "<a href ='".BASE_URL."includes/logic/suaKH.php?id_cus=".$row3['id_cus']."&action=coppy&nv=".$ruser['real_name']." 'class='btn btn-sm btn-info'>x2</a>";
                            echo "&nbsp";
                            echo"
                            <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#my".$row3['id_cus']."'>Hủy</button>

                                  <!-- Modal -->
                                  <div id='my".$row3['id_cus']."' class='modal fade' role='dialog'>
                                  <div class='modal-dialog'>

                                      <!-- Modal content-->
                                      <div class='modal-content'>
                                          <form action='includes/logic/deleteKH.php' method='GET'>
                                          <div class='modal-header'>
                                              <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                              <h4 class='modal-title'>Nguyên Nhân Hủy</h4>
                                          </div>

                                          <div class='modal-body'>
                                              <input type='hidden' name='hd' value='huy'>
                                              <input type='hidden' name='id_cus' value='".$row3['id_cus']."'>
                                             <textarea style = 'width:100%' name='nnHuy'></textarea>
                                          </div>

                                          <div class='modal-footer'>
                                              <input type='submit' class='btn btn-default' value='Xác Nhận'/>
                                          </div>
                                      </form>
                                      </div>

                                  </div>
                                  </div>
        
                  </td>
                  
           </tr>";
                   endwhile;
           
       echo "</tbody>
       
   </table>	

 ";


    ?>
<h3> Lịch Đã Phân </h3>
 
<table class="table table-bordered table-hover ">
        <thead>
            <tr>
                 <th class="col-md-2">Yêu Cầu CV</th>
                <th class="col-md-1">Tên KH</th>
                <th class="col-md-2">Địa Chỉ</th>
                <th class="col-md-0.5">Quận</th>
                
                <th class="col-md-1">Số Điện Thoại</th>
               
                <th class="col-md-1">Ghi chú</th>
                <th class="col-md-0.5">Tên Thợ</th>
                
				
                
                <th class="col-md-0.5">Thu Chi</th>
                <th class="col-md-2">Thay đổi</th>
            </tr>   
        </thead>
        <tbody>
            <?php while ($row = $q->fetch()): ?>
                        <tr>
							 <td><?php echo htmlspecialchars($row['yc_book']); ?></td>
                            <td><?php echo htmlspecialchars($row['name_cus']); ?></td>
                            
                            <td><?php echo htmlspecialchars($row['add_cus']); ?></td>
                            <td><?php echo htmlspecialchars($row['des_cus']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone_cus']); ?></td>
                           
                            <td><?php echo htmlspecialchars($row['note_book']); ?></td>
                            <td><?php echo htmlspecialchars($row['name_worker']); ?></td>
                           
                            <td>
                            <?php 
                                echo "<a href ='".BASE_URL."includes/logic/thu_chi.php?id_work=".$row['id_work']."&idq=1&ki=3'class='btn btn-sm btn-success'>Nhập</a>";
                                echo " </td> <td>";
								echo "<a href ='".BASE_URL."includes/logic/XL_sua_lich_da_phan.php?id_work=".$row['id_work']."'class='btn btn-sm btn-warning'>Sửa</a>";
							 	echo "&nbsp";
                                echo"<a href ='".BASE_URL."includes/logic/deleteKH.php?hd=ks&id_cus=".$row['id_cus']."'class='btn btn-sm btn-primary' >KSát</a>";
                                echo "&nbsp";
                                echo"<button type='button' class='btn btn-sm btn-warning' data-toggle='modal' data-target='#my".$row['id_cus']."'>Hủy</button>";
								 echo "&nbsp";
							 	echo "<a href ='".BASE_URL."includes/logic/suaKH.php?id_cus=".$row['id_cus']."&action=sua&nv=".$ruser['real_name']." 'class='btn btn-sm btn-success'> Sửa</a>

                                <!-- Modal -->
                                <div id='my".$row['id_cus']."' class='modal fade' role='dialog'>
                                <div class='modal-dialog'>

                                    <!-- Modal content-->
                                    <div class='modal-content'>
                                        <form action='includes/logic/deleteKH.php' method='GET'>
                                        <div class='modal-header'>
                                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                            <h4 class='modal-title'>Nguyên Nhân Hủy</h4>
                                        </div>

                                        <div class='modal-body'>
                                            <input type='hidden' name='hd' value='huy'>
											 <input type ='hidden' name='ki' value ='3' />
                                            <input type='hidden' name='id_cus' value='".$row['id_cus']."'>
                                           <textarea style = 'width:100%' name='nnHuy'></textarea>
                                        </div>

                                        <div class='modal-footer'>
                                            <input type='submit' class='btn btn-default' value='Xác Nhận'/>
                                        </div>
                                    </form>
                                    </div>

                                </div>
                                </div>";
                                echo "<a href ='".BASE_URL."includes/logic/thuhoi.php?id_cus=".$row['id_cus']."&ki=3 'class='btn btn-sm btn-danger'>Trả</a>";
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
            
        </tbody>
    </table>	