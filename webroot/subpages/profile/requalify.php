<?php
 if($this->request->session()->read('debug'))
        echo "<span style ='color:red;'>subpages/profile/requalify.php #INC1181</span>";
 ?>
<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-briefcase"></i>Profile Crons (Requalification)
        </div>
    </div>
    <div class="portlet-body">
    
        <div class="table-scrollable">
        
            <table
                class="table table-condensed  table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Scheduled Date</th>
                        <th>Client</th>
                        <th>Requalifed Profile</th>
                        <th>Hired Date</th>
                        <th>Status</th>
                        <th>Manual</th>
                    </tr>
                </thead>
                <tbody class="allct">
                <?php
                $today = date('Y-m-d');
                $k=0;
                foreach($requalify as $k=>$d) {
                    $Profile = $this->requestAction('/settings/getprofile/'.$d->profile_id);
                    if($Profile) {
                        ?>
                        <tr>
                            <td><?php echo ++$k; ?></td>
                            <td><?php echo $d->cron_date; ?></td>
                            <td>
                                <a href="<?= $this->request->webroot; ?>clients/edit/<?= $d->client_id; ?>?view"><?php echo $this->requestAction('/settings/getclient/' . $d->client_id); ?></A>
                            </td>
                            <td><?= $Profile->username; ?></td>
                            <td><?php echo $Profile->hired_date;?></td>
                            <td>Requalifed</td>
                            <td><?php echo ($d->manual == '1') ? 'Yes' : 'No'; ?></td>
                        </tr>
                        <?php
                    }
                }
                foreach($new_req as $d) {
                    $fname = explode(',',$d['forms']);
                    $new_form = "";
                    foreach($fname as $n) {
                        if($n=='1')
                            $nam = 'MVR';
                        elseif($n=='14')
                            $nam = 'CVOR';
                        elseif($n=='72')
                            $nam = 'DL';
                        $new_form .=$nam.","; 
                        
                    }
                    $Profile = $this->requestAction('/settings/getprofile/'.$d['profile_id']);
                    ?>
                    <tr>
                        <td><?php echo ++$k;?></td>
                        <td><?php echo $d['cron_date'];?></td>
                        <td><a href="<?= $this->request->webroot;?>clients/edit/<?= $d['client_id']; ?>?view"><?php echo $this->requestAction('/settings/getclient/'.$d['client_id']);?></A></td>
                        <td><a href="<?= $this->request->webroot;?>profiles/view/<?php echo $d['profile_id'];?>"><?php echo $Profile->username;?></a></td>
                        <td><?php echo $Profile->hired_date;?></td>
                        <td><?php $status= $this->requestAction('/rapid/check_status/'.$d['cron_date'].'/'.$d['client_id'].'/'.$d['profile_id']); if($status=='0'){?>Scheduled for requalification<BR>(products: <?php echo substr($new_form,0,strlen($new_form) - 1);?>)</td>
                        <td><?php if(strtotime($d['expiry_date'])>= strtotime($d['cron_date'])){?><a href="<?php echo $this->request->webroot."rapid/cron_user/".$d['cron_date']."/".$d['client_id']."/".$d['profile_id'];?>" class="btn btn-primary btn-xs" style="width: 100%;">Send Now</a><?php } else { echo "This driver expires on ".$d['expiry_date'];} }else echo "Manually Requalifed</td><td>";?></td>

                    </tr>        
                <?php
                    unset($new_form);
                }
                ?>
        </tbody>
        </table>
        
    </div>
    </div>
</div>
