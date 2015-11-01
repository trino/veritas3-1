<div class="form-group row col-md-12 splitcols" ID="GNDN">
    <div class="col-md-3"><label class="control-label">Your Username: </label>
        <input type="text" class="form-control required" required name="username" />
    </div>
    <div class="col-md-3"><label class="control-label">Your <?= $strings["forms_password"]; ?>: </label>
        <input type="password" class="form-control required" required name="password" />
    </div>
    <div class="col-md-3"><label class="control-label">Order Number: </label>
        <input type="number" class="form-control required" required name="orderid" />
    </div>
    <div class="col-md-3"><label class="control-label">Pretty: </label><BR>
        <LABEL valign="center"><input type="checkbox" class="form-control" name="pretty" value="true"/> Yes</LABEL>
    </div>
</div>