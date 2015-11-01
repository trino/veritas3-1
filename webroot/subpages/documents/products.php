<?php
 if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/documents/products.php #INC148</span>";}

function pending($name, $value, $checked = true){
    $value = '<input type="checkbox" name="' . $name . '" value="' . $value . '"'; // checked>';
    if ($checked) { return $value . " checked>";}
    return $value . ">";
}
?>
<form id="form_products">
    <input class="document_type" type="hidden" name="document_type" value="Products" />

    <input type="hidden" class="sub_docs_id" name="sub_doc_id" value="9" id="af" />
    <div class="clearfix"></div>

<div class="portlet box yellow">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-folder-open-o"></i>ISB MEE Products
        </div>
    </div>
    <div class="portlet-body">
        <div class="table-scrollable">
            <table class="table ">
                <tbody>

                    <tr class="even" role="row">

                        <td>                            <?php echo pending("prem_nat", "PremiumNational", false); ?>
                             <span class="icon-doc"></span> Premium National Criminal Record Check</td>
                    </tr>

                    <tr class="even" role="row">

                        <td>                            <?php echo pending("dri_abs", "Driver's Record Abstract", false); ?>
                             <span class="icon-doc"></span> Driver's Record Abstract</td>
                    </tr>

                    <tr class="even" role="row">

                        <td>                            <?php echo pending("CVOR", "CVOR", false); ?>
                             <span class="icon-doc"></span> CVOR</td>
                    </tr>


                    <tr class="odd" role="row">

                        <td>                            <?php echo pending("prem_nat", "PremiumNational", false); ?>
                             <span class="icon-doc"></span> Pre-employment Screening Program Report</td>
                    </tr>


                    <tr class="even" role="row">

                        <td>                            <?php echo pending("prem_nat", "PremiumNational", false); ?>
                             <span class="icon-doc"></span> Transclick</td>
                    </tr>


                    <tr class="odd" role="row">

                        <td>                            <?php echo pending("prem_nat", "PremiumNational", false); ?>
                             <span class="icon-doc"></span> Certifications</td>
                    </tr>

                    <tr class="odd" role="row">

                        <td>                             <?php echo pending("prem_nat", "PremiumNational", false); ?>
                             <span class="icon-doc"></span> Letter of Experience</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
</Form>