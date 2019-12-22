<?php

require_once __DIR__ . '/fpdf.php';

class Pdf extends FPDF
{
    private $B=0;
    private $I=0;
    private $U=0;
    private $HREF='';
    private $ALIGN='';

    private $angle=0;

    private $TableWidths;
    private $TableHAligns;
    private $TableFonts;

    // Rotate

    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }

    public function Rotate($angle,$x=-1,$y=-1)
    {
        if($x==-1)
            $x=$this->x;
        if($y==-1)
            $y=$this->y;
        if($this->angle!=0)
            $this->_out('Q');
        $this->angle=$angle;
        if($angle!=0)
        {
            $angle*=M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
        }
    }

    public function SetTableWidths(array $w)
    {
        //Set the array of column TableWidths
        $this->TableWidths=$w;
    }

    public function SetTableHAligns(array $a)
    {
        //Set the array of column alignments
        $this->TableHAligns=$a;
    }

    /**
     * @param array $f values [
     *                          ['family' => 'arial','style' => 'B','size' => '12'],
     *                          ['family' => 'arial','style' => 'B','size' => '12']
     *                      ]
     */
    public function SetTableFonts(array $f){
        //Set the array of column fonts
        $this->TableFonts = $f;
    }

    /**
     * @param array $data text content
     * @param int $h Line height row
     * @param bool $fill fill row
     * @param string $style R = Rectangle | H = Horizontal lines | V = Vertical lines
     */
    public function TableRow(array $data, $h = 4, $fill = false, $style = 'R')
    {
        //Calculate the height of the TableRow
        $nb = 0;
        for($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this -> TableNbLines( $this -> TableWidths[$i], $data[$i]));
        $nh = $h * $nb;

        //Issue a page break first if needed
        $this->TableCheckPageBreak($nh);

        //Draw the cells of the TableRow
        for($i = 0; $i < count($data); $i++)
        {
            // Widths
            $w=$this->TableWidths[$i];
            // Aligns
            $a=isset($this->TableHAligns[$i]) ? $this->TableHAligns[$i] : 'L';
            // Fonts
            $fFamily = isset($this->TableFonts[$i]) ? $this->TableFonts[$i]['family'] ?? 'undefined' : 'undefined';
            $fStyle = isset($this->TableFonts[$i]) ? $this->TableFonts[$i]['style'] ?? 'undefined' : 'undefined';
            $fSize = isset($this->TableFonts[$i]) ? $this->TableFonts[$i]['size'] ?? 'undefined' : 'undefined';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();

            //Draw the border
            switch ($style){
                case 'V':
                    if ($fill)
                        $this -> Rect($x,$y,$w,$nh,'F');
                    $this -> Line($x, $y, $x,$y + $nh);
                    $this -> Line($x + $w, $y,$x + $w,$y + $nh);
                    break;
                case 'H':
                    if ($fill)
                        $this -> Rect($x,$y,$w,$nh,'F');
                    $this -> Line($x, $y + $nh, $x + $w,$y + $nh);
                    $this -> Line($x, $y, $x + $w,$y);
                    break;
                case 'R':
                    if ($fill){
                        $this -> Rect($x,$y,$w,$nh,'DF');
                    }else{
                        $this -> Rect($x,$y,$w,$nh);
                    }
                    break;
            }
            // Font
            if ($fSize != 'undefined'){
                $this->SetFontSize($fSize);
            }
            if ($fFamily != 'undefined'){
                $this->SetFont($fFamily);
            }
            if ($fStyle != 'undefined'){
                $this->SetFont('',$fStyle);
            }
            //Print the text
            $this->MultiCell($w,$h,$data[$i],0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($nh);
    }

    private function TableCheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    private function TableNbLines($w,$txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb)
        {
            $c=$s[$i];
            if($c=="\n")
            {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

    // Write HTML
    public function WriteHTML($html, $h = 5)
    {
        //HTML parser
        $html=str_replace("\n",' ',$html);
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                elseif($this->ALIGN=='center')
                    $this->Cell(0,$h,$e,0,1,'C');
                else
                    $this->Write($h,$e);
            }
            else
            {
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract properties
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $prop=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $prop[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$prop,$h);
                }
            }
        }
    }

    private function OpenTag($tag,$prop,$h)
    {
        //Opening tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF=$prop['HREF'];
        if($tag=='BR')
            $this->Ln($h);
        if($tag=='P')
            $this->ALIGN=$prop['ALIGN'];
        if($tag=='HR')
        {
            if( !empty($prop['WIDTH']) )
                $Width = $prop['WIDTH'];
            else
                $Width = $this->w - $this->lMargin-$this->rMargin;
            $this->Ln(2);
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.4);
            $this->Line($x,$y,$x+$Width,$y);
            $this->SetLineWidth(0.2);
            $this->Ln(2);
        }
    }

    private function CloseTag($tag)
    {
        //Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='P')
            $this->ALIGN='';
    }

    private function SetStyle($tag,$enable)
    {
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s)
            if($this->$s>0)
                $style.=$s;
        $this->SetFont('',$style);
    }

    private function PutLink($URL,$txt,$h)
    {
        //Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write($h,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }

    // Character UTF8
    public function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $str = utf8_decode($txt);
        parent::Cell($w, $h, $str, $border, $ln, $align, $fill, $link);
    }

    // Rectangle
    public function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    public function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
}