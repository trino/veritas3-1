<H2>Mass translation string system</H2><?php if($data){ echo $data;} else { echo "Page, table and language are reserved veriables<BR>The text is exploded based on =";} ?>
<FORM METHOD="post">
    <TEXTAREA NAME="data" STYLE="width: 100%; height: 500px;"><?php
        if ($page){
            $CRLF = "\r\n";
            echo "page=". $page . $CRLF;
            echo "language=". $language . $CRLF;
        } elseif(isset($text)) {
            echo $text;
        }
        ?></TEXTAREA>
    <BR>
    <INPUT TYPE="submit">
</FORM>