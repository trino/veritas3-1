<?php
    if ($this->request->session()->read('debug')) {
        echo "<span style ='color:red;'>subpages/profile/feedback.php #INC124445</span>";
    }

    $feedbacks = $this->requestAction('/settings/get_fedbacks/'.$id);
?>

<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-briefcase"></i>Surveys Submitted
        </div>
    </div>
    <div class="portlet-body">
        <?php if($feedbacks!="" && count($feedbacks)>0){?>
        <div class="table-scrollable">

            <table
                class="table table-condensed  table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Survey Name</th>
                    <th>Submitted On</th>
                    <th>Actions</th>

                </tr>
                </thead>
                <tbody class="allpt">
                <?php 
                if(isset($p))
                {
                    if($p->profile_type =='5')
                    {
                        $form = '60 day survey';
                        $a = '60days.php';
                    }
                    elseif($p->profile_type == '9' || $p->profile_type == '12')
                    {
                        $form = '30 day survey';
                        $a = '30days.php';
                    }   
                }
                foreach($feedbacks as $f)
                {
                    
                    ?>
                <tr>
                    <td><?php echo $k+1;?></td>
                    <td><?php echo $form;?></td>
                    <td><?php echo $f->created;?></td>
                    <td><a href="<?php echo $this->request->webroot;?>application/<?php echo $a.'?p_id='.$id.'&form_id='.$f->id;?>" class="btn btn-primary editptype" id="editptype_1" target="_blank">View</a></td>
                </tr>
                <?php }?>
                
                </tbody>
            </table>

        </div>
        <?php }
        else
        {
            echo "<strong>No feedback submitted yet!</strong>";
        }?>
    </div>
</div>
