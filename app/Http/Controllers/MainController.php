<?php

namespace App\Http\Controllers;



use App\Models\Repconc;
use App\Models\BarsuNir;
use App\Models\BarsuNirDop;
use App\Models\HudredIdeas;
use App\Models\MolInic;
use App\Models\MolIndic;
use App\Models\Gpni;
use Illuminate\Support\Facades\Response;
use App\Models\GpniDop;
use App\Models\Grant;
use App\Models\RcpiStratCheckboxes;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Auth;
use Aneeskhan47\PaginationMerge\Facades\PaginationMerge;
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\SimpleType\Jc;
use Illuminate\Support\Facades\Storage;
use Aspose\Words\WordsApi;
use App\Http\Controllers\Settings;
use App\Http\Controllers\ConvertDocumentRequest;
use App\Models\Gpni_calculate;
use App\Models\Gpni_obosn;
use App\Models\Gpni_plan;
use App\Models\grant_calculate;
use App\Models\grant_obosn;
use App\Models\RcpiBp;
use App\Models\RcpiPass;
use App\Models\RcpiPassCheckboxes;
use App\Models\RcpiStrat;
use App\Models\RcpiTeo;
use App\Models\User;

require_once base_path('vendor/autoload.php');
class MainController extends Controller
{
    public function form1(){
        return view('forms/form1');
    }
    public function form11($name,$id){
      
        if($name=="Молодежные инициативы"){
        $molIndic=MolIndic::where('project_id', $id)->get();
        $molInic=MolInic::find($id);
        if($molInic->user_id==Auth::user()->id || Auth::user()->Role=='Admin'){
        return view('forms/form11',compact('molInic','molIndic'));
        }
        else{
            return redirect('cabinet');
        }
    }
        if($name=="Участие в НИР"){
            $barsunir=BarsuNir::find($id);
            $barsunirdop=BarsuNirDop::where("project_id", $id)->get();
            if($barsunir->user_id==Auth::user()->id || Auth::user()->Role=='Admin')
            {
            
                return view("forms/form22",compact("barsunir","barsunirdop"));
        
            }
        else
        {    return redirect('cabinet');}
        
    }
        if($name== "100 ИДЕЙ ДЛЯ БЕЛАРУСИ"){
            $hundredideas=HudredIdeas::find($id);
            if($hundredideas->user_id==Auth::user()->id || Auth::user()->Role=='Admin')
            {
            
                return view("forms/form33",compact("hundredideas"));
        
            }
            else{
                return redirect("cabinet");
            }
        }
        if($name== "ГПНИ"){
            $gpni=Gpni::find($id);
            $gpniDop=GpniDop::where("project_id", $id)->get();
            $gpni_plan=Gpni_plan::where("project_id", $id)->get();
            $gpni_calculate=Gpni_calculate::where("project_id", $id)->get();
            $gpni_obosn=Gpni_obosn::where("project_id", $id)->get();
            if($gpni->user_id==Auth::user()->id || Auth::user()->Role== "Admin")
            {
            return view("forms/form44",compact("gpni","gpniDop","gpni_plan","gpni_calculate","gpni_obosn"));
        }
        else{
            return redirect("cabinet");
        }
        }
        if($name== "Заявка на получение гранта"){
            $grant=Grant::find($id);
            $grant_calculate=grant_calculate::where("project_id", $id)->get();
            $grant_obosn=grant_obosn::where("project_id", $id)->get();
            if($grant->user_id==Auth::user()->id || Auth::user()->Role== "Admin"){
            return view("forms/form55",compact("grant","grant_calculate","grant_obosn"));
        }
        
        else{
            return redirect("cabinet");
        }

        }
        if($name== "РКИП"){
            $repconc=Repconc::find($id);
            $repconc_strat_checkbox=RcpiStratCheckboxes::where("project_id", $repconc->id)->get();
            $rcpistrat=RcpiStrat::where("project_id", $repconc->id)->get();
            $rcpibp=RcpiBp::where("project_id", $repconc->id)->get();
            $rcpipass_checkbox=RcpiPassCheckboxes::where("project_id", $repconc->id)->get();
            $rcpipass=RcpiPass::where("project_id", $repconc->id)->get();
            $rcpiteo=RcpiTeo::where("project_id", $repconc->id)->get();
            $user=User::where("id",$repconc->user_id)->get();
            if($repconc->user_id==Auth::user()->id || Auth::user()->Role== "Admin"){
            if($user->first()->age>30){
            return view("forms/form66",compact("repconc","repconc_strat_checkbox","rcpistrat","rcpipass_checkbox","rcpipass","rcpibp","user"));
            }
            else {
                return view("forms/form66",compact("repconc","repconc_strat_checkbox","rcpistrat","rcpipass_checkbox","rcpipass","rcpiteo","user"));
            }
        }
        
        else{
            return redirect("cabinet");
        }

        }


    }
    public function form_word($name,$id)
    {  
        if($name=="Молодежные инициативы"){
            $molIndic=MolIndic::where('project_id', $id)->get();
            $molInic=MolInic::find($id);
            //$this->form1_word($molIndic,$molInic);
            $phpWord= new PhpWord();
            $phpWord->setDefaultFontName('Times New Roman');
            $phpWord->setDefaultFontSize(14);
            $templateProcessor= new TemplateProcessor('templates\form1.docx');
            $templateProcessor->deleteBlock('tableRow');
            $index=0;
            $templateProcessor->setValue('projectName',$molInic->nameProject);
            $templateProcessor->setValue('regionName',$molInic->nameRegion);
            $templateProcessor->setValue('locality',$molInic->namePunct);
            $templateProcessor->setValue('description',$molInic->descriptionProblem);
            $templateProcessor->setValue('realizationTemp',$molInic->realizationTemp);
            $templateProcessor->setValue('fioRuk',$molInic->fioRuk);
            $templateProcessor->setValue('phone',$molInic->phone);
            $templateProcessor->setValue('email',$molInic->email);
            $templateProcessor->setValue('sostav',$molInic->sostav);
            $templateProcessor->setValue('dopInformation',$molInic->dopInformation);
            $indicator = array();
            foreach($molIndic as $mol)
            {
                array_push($indicator,$mol->indicator);
            }
            $valueIndicator = array();
            foreach($molIndic as $mol)
            {
                array_push($valueIndicator,$mol->value);
            }
            
            
            $templateProcessor->setValue('indicator',$indicator[0]);
            
            
            $section = $phpWord->addSection();
            $header = array('size' => 16, 'bold' => true);
        
        
            // 2. Advanced table
        
            $section->addTextBreak(1);
            $styleCell =
            array(
            'borderColor' =>'000000',
            'borderSize' => 5,
            'valign' => 'center',
            );
            $styleText = array(
                'name' => 'Times New Roman',
                'size' => 14, // Размер шрифта в точках
            );
        
            $table = $section->addTable($styleCell);
            $table->addRow(900);
            $table->addCell(800, $styleCell)->addText('№ п/п',$styleText);
            $table->addCell(4800, $styleCell)->addText('Показатель, единиц измерения',$styleText);
            $table->addCell(4000, $styleCell)->addText('Значение показателя',$styleText);
            for ($i = 0; $i < count($indicator); $i++) {
                $table->addRow();
                $table->addCell(800)->addText($i+1,$styleText);
                $table->addCell(4800)->addText($indicator[$i],$styleText);      
                $table->addCell(4000)->addText($valueIndicator[$i],$styleText);
            };
            
           
        
                $templateProcessor->setComplexBlock('mainTable',$table);
            $newFileName = $name."_".$id;
           
            $templateProcessor->saveAs($newFileName.'.docx');
            $filePath = public_path($newFileName.'.docx');
            return response()->download($filePath)->deleteFileAfterSend();
        }
        if($name=="РКИП")
        {
            $phpWord=new PhpWord();
            $phpWord->setDefaultFontName('Times New Roman');
            $phpWord->setDefaultFontSize(14);
            $repconc=Repconc::find($id);
            $templateProcessor= new TemplateProcessor('templates\form6.docx');
            $templateProcessor->setValue('nominationName',$repconc->nominationName);
            $templateProcessor->setValue('nameProject',$repconc->nameProject);
            $templateProcessor->setValue('fio',$repconc->fio);
            $templateProcessor->setValue('teachWorkPlace',$repconc->teachWorkPlace);
            $templateProcessor->setValue('dolzhnUch',$repconc->dolzhnUch);
            $templateProcessor->setValue('uchStep',$repconc->uchStep);
            $templateProcessor->setValue('adress',$repconc->adress);
            $templateProcessor->setValue('phone',$repconc->phone);
            $templateProcessor->setValue('email',$repconc->email);
            $templateProcessor->setValue('projectLink',$repconc->projectLink);
            $templateProcessor->setValue('yurName',$repconc->yurName);
            $templateProcessor->setValue('fioRuk',$repconc->fioRuk);
            $templateProcessor->setValue('dolzhnYur',$repconc->dolzhnYur);
            $templateProcessor->setValue('yurStep',$repconc->yurStep);
            $templateProcessor->setValue('yurAdress',$repconc->yurAdress);
            $templateProcessor->setValue('platNumber',$repconc->platNumber);
            $templateProcessor->setValue('yurPhone',$repconc->yurPhone);
            $templateProcessor->setValue('yurEmail',$repconc->yurEmail);
            $templateProcessor->setValue('fioCommand',$repconc->fioCommand);
            $templateProcessor->setValue('yurLink',$repconc->yurLink);
            $newFileName = $name."_".$id;
           
            $templateProcessor->saveAs($newFileName.'.docx');
            $filePath = public_path($newFileName.'.docx');
            $rcpicheck=RcpiStratCheckboxes::where('project_id',$repconc->id)->get();
            $rcpiInputs=RcpiStrat::where('project_id',$repconc->id)->get();
            $rcpiPassInputs=RcpiPass::where("project_id",$repconc->id)->get();
            $rcpiPassCheck=RcpiPassCheckboxes::where("project_id",$repconc->id)->get();
            $rcpistrat=$this->form6_strategy_word($rcpicheck,$rcpiInputs,$newFileName,$repconc);
            $rcpipass=$this->form6_pass_word($rcpiPassCheck,$rcpiInputs,$newFileName);
            $user=User::where("id",$repconc->user_id)->get();
            if($user->first()->age>30){
            $rcpibp=$this->form6_bp_word($repconc->id,$newFileName);
            }
            else{
                $rcpiteo=$this->form6_teo_word($repconc->id,$newFileName);
            }

            $zip_file = $newFileName.'.zip'; // Name of our archive to download

    // Initializing PHP class
    $zip = new \ZipArchive();
    $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
    
    $firstfile=$newFileName.'.docx';
    // Adding file: second parameter is what will the path inside of the archive
    // So it will create another folder called "storage/" inside ZIP, and put the file there.
    $zip->addFile($firstfile);
    $zip->addFile($rcpistrat.'.docx');
    $zip->addFile($rcpipass.'.docx');
    if($user->first()->age>30){
    $zip->addFile($rcpibp.'.docx');
    }
    else{
        $zip->addFile($rcpiteo.'.docx');
    }

    $zip->close();
    unlink(public_path($firstfile));
    unlink(public_path($rcpistrat.'.docx'));
    unlink(public_path($rcpipass.'.docx'));
    if($user->first()->age>30){
    unlink(public_path($rcpibp.'.docx'));
    }
    else{
        unlink(public_path($rcpiteo.'.docx'));
    }            
        return response()->download($zip_file)->deleteFileAfterSend();


        }
        if($name=="Участие в НИР")
        {
            $phpWord= new PhpWord();
    
            $phpWord->setDefaultFontName('Times New Roman');
            $phpWord->setDefaultFontSize(14);
            $barsunirdop=BarsuNirDop::where('project_id', $id)->get();
            $barsunir= BarsuNir::find($id);
            $templateProcessor= new TemplateProcessor('templates\form2.docx');
      
            $templateProcessor->deleteBlock('tableRow');
            $index=0;
            $templateProcessor->setValue('sinceDir',$barsunir->sinceDir);
        
            $templateProcessor->setValue('workTheme',$barsunir->workTheme);
            $templateProcessor->setValue('sinceElem',$barsunir->sinceElem);
            $templateProcessor->setValue('nirRuks',$barsunir->nirRuks);
            $templateProcessor->setValue('realizationTemp',$barsunir->realizationTemp);
            $templateProcessor->setValue('phone',$barsunir->phone);
            $templateProcessor->setValue('obosnovanie',$barsunir->obosnovanie);
            $templateProcessor->setValue('goalsNir',$barsunir->goalsNir);
            $templateProcessor->setValue('ozhidResult',$barsunir->ozhidResult);
            $templateProcessor->setValue('praktZnach',$barsunir->praktZnach);
            $section=$phpWord->addSection();
        
    
        $newFileName = time();
        $section->addTextBreak(1);
        $styleCell =
        array(
        'borderColor' =>'000000',
        'borderSize' => 3,
        'valign' => 'center',
        );
        $styleText = array(
            'name' => 'Times New Roman',
            'valign'=>'center',
            'size' => 14,
        );
    
    
        $workEtap=array();
        $nachSrok=array();
        $endSrok=array();
        $kontrResult=array();
        foreach($barsunirdop as $item)
        {
            array_push($workEtap,$item->workEtap);
            array_push($nachSrok,$item->nachSrok);
            array_push($endSrok,$item->endSrok);
            array_push($kontrResult,$item->kontrResult);
        }
    
        $table = $section->addTable($styleCell);
        for ($i = 0; $i < count($workEtap); $i++) {
        $table->addRow(200);
        $table->addCell(1090, $styleCell)->addText($i,$styleText);
        $table->addCell(3588, $styleCell)->addText($workEtap[$i],$styleText);
        $table->addCell(1385, $styleCell)->addText($nachSrok[$i],$styleText);
        $table->addCell(1470, $styleCell)->addText($endSrok[$i],$styleText);
        $table->addCell(1900, $styleCell)->addText($kontrResult[$i],$styleText);
    
        }
       $filename=$name."_".$id;
        $templateProcessor->setComplexBlock('table',$table);
        $templateProcessor->saveAs($filename.'.docx');
        $filePath = public_path($filename.'.docx');
        return response()->download($filePath)->deleteFileAfterSend();

        }
        if($name=="100 ИДЕЙ ДЛЯ БЕЛАРУСИ"){
            $phpWord= new PhpWord();
            $hundreadideas=HudredIdeas::find($id);
            $name_project = $hundreadideas->name_project;
            $name_autors = $hundreadideas->name_autors;
            $relevance = $hundreadideas->relevance;
            $goals_objectives = $hundreadideas->goals_objectives;
            $advantages_project = $hundreadideas->advantages_project;
            $property_protection = $hundreadideas->property_protection;
            $offers = $hundreadideas->offers;
            $phpWord->setDefaultFontName('Times New Roman');
            $phpWord->setDefaultFontSize(14);
        
        
          
            $templateProcessor= new TemplateProcessor('templates\form3.docx');
          
            $templateProcessor->deleteBlock('tableRow');
            $index=0;
            $templateProcessor->setValue('name_project',$name_project);
            $templateProcessor->setValue('name_autors',$name_autors);
            $templateProcessor->setValue('relevance',$relevance);
            $templateProcessor->setValue('goals_objectives',$goals_objectives);
            $templateProcessor->setValue('advantages_project',$advantages_project);
            $templateProcessor->setValue('property_protection',$property_protection);
            $templateProcessor->setValue('offers',$offers);
            $newFileName = $name.'_'.$id;
            $templateProcessor->saveAs($newFileName.'.docx');
            
    return response()->download($newFileName.'.docx')->deleteFileAfterSend();
        }
        if($name=="ГПНИ"){
            $phpWord= new PhpWord();
            $gpni=Gpni::find($id);
            $sinceDir= $gpni->sincedir;
            $number= $gpni->number;
            $data= $gpni->data;
            $year= $gpni->year;
            $nameN= $gpni->nameN;
            $nameP= $gpni->nameP;
            $orgZav= $gpni->orgZav;
            $nach= $gpni->nach;
            $end= $gpni->end;
            $allCost= $gpni->allCost;
            $fin1= $gpni->fin1;
            $fin2= $gpni->fin2;
            $fin3= $gpni->fin3;
            $gpnidop=GpniDop::where('project_id', $id)->get();
            $fio=array();
            $uchStep=array();
            $uchZav=array();
            $kafLab=array();
            $phone=array();
            $email=array();
            foreach($gpnidop as $item)
            {
                array_push($fio,$item->fio);
                array_push($uchStep, $item->uchStep);
                array_push($uchZav,$item->uchZav);
                array_push($kafLab,$item->kafLab);
                array_push($phone,$item->phone);
                array_push($email,$item->email);
            }
            $templateProcessor= new TemplateProcessor('templates\form4.docx');
  
  
    $index=0;
    $templateProcessor->setValue('sinceDir',$sinceDir,);
    $templateProcessor->setValue('nach',$nach);
    $templateProcessor->setValue('number',$number);
    $templateProcessor->setValue('data',$data);
    $templateProcessor->setValue('year',$year,);
    $templateProcessor->setValue('nameN',$nameN);
    $templateProcessor->setValue('nameP',$nameP);
    $templateProcessor->setValue('orgZav',$orgZav);
    $templateProcessor->setValue('end',$end);
    $templateProcessor->setValue('allCost',$allCost);
    $templateProcessor->setValue('fin1',$fin1);
    $templateProcessor->setValue('fin2',$fin2);
    $templateProcessor->setValue('fin3',$fin3);
    $newFileName = time();

    
    $section=$phpWord->addSection();
    $section->addTextBreak(1);
    $styleCell =
    array(
    'borderColor' =>'000000',
    'borderSize' => 3,
    'valign' => 'center',
    'cellMargin' => 100,
    );
    $styleText = array(
        'name' => 'Times New Roman',
        'valign'=>'center',
        'size' => 12,
    );

    $table = $section->addTable($styleCell);
    for ($i = 0; $i < count($fio); $i++) {
        $section->addTextBreak();
        $table->addRow(200);
    $table->addCell(5850, $styleCell)->addText("Ф.И.О. (полное) ",$styleText);
    $table->addCell(4400, $styleCell)->addText($fio[$i],$styleText);
    $table->addRow(200);
    $table->addCell(5850, $styleCell)->addText("Ученая степень ",$styleText);
    $table->addCell(4400, $styleCell)->addText($uchStep[$i],$styleText);
    $table->addRow(200);
    $table->addCell(5850, $styleCell)->addText("Ученое звание ",$styleText);
    $table->addCell(4400, $styleCell)->addText($uchZav[$i],$styleText);
    $table->addRow(200);
    $table->addCell(5850, $styleCell)->addText("Кафедра, лаборатория",$styleText);
    $table->addCell(4400, $styleCell)->addText($kafLab[$i],$styleText);
    $table->addRow(200);
    $table->addCell(5850, $styleCell)->addText("Телефон служебный (с кодом города), мобильный с указанием кода оператора",$styleText);
    $table->addCell(4400, $styleCell)->addText($phone[$i],$styleText);
    $table->addRow(200);
    $table->addCell(5850, $styleCell)->addText("Контактный e-mail",$styleText);
    $table->addCell(4400, $styleCell)->addText($email[$i],$styleText);
    

    

    }
    $newFileName = $name.'_'.$id;
    $templateProcessor->setComplexBlock('table',$table);
    $templateProcessor->saveAs($newFileName.'.docx');
    $zip_file = $newFileName.'.zip'; // Name of our archive to download

    // Initializing PHP class
    $zip = new \ZipArchive();
    $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
    
    $firstfile=$newFileName.'.docx';
    $plan=$this->form4_plan_word($id,$name).'.docx';
    $calculate=$this->form4_plan_calculate($id,$name).'.docx';
    $obosn=$this->form4_obosn_word($id,$name).'.docx';
    // Adding file: second parameter is what will the path inside of the archive
    // So it will create another folder called "storage/" inside ZIP, and put the file there.
    $zip->addFile($firstfile);
    $zip->addFile($plan);
    $zip->addFile($calculate);
    $zip->addFile($obosn);
    $zip->close();
    unlink(public_path($plan));
    unlink(public_path($firstfile));
    unlink(public_path($obosn));
    unlink(public_path($calculate));
    unlink(public_path($calculate));
                
        return response()->download($zip_file)->deleteFileAfterSend();
    

    




        }
        if($name=="Заявка на получение гранта"){
            $grant=Grant::find($id);
            $scienceDirection=$grant->sienceDirection;
            $fioGrad=$grant->fioGrad;
            $currentYear=date("Y");
            $grandCategory=$grant->grandCategory;
            $workName=$grant->workName;
            $disertationTheme=$grant->disertationTheme;
            $uchrName=$grant->uchrName;
            $special=$grant->special;
            $knowledge=$grant->knowledge;
            $templateProcessor= new TemplateProcessor('templates\form5.docx');
            $templateProcessor->setValue('scienceDirection',$scienceDirection);
            $templateProcessor->setValue('fioGrad',$fioGrad);
            $templateProcessor->setValue('grandCategory',$grandCategory);
            $templateProcessor->setValue('workName',$workName);
            $templateProcessor->setValue('disertationTheme',$disertationTheme);
            $templateProcessor->setValue('uchrName',$uchrName);
            $templateProcessor->setValue('special',$special);
            $templateProcessor->setValue('knowledge',$knowledge);
            $templateProcessor->setValue('currentYear',$currentYear);
            $newFileName = $name.'_'.$id;
            $templateProcessor->saveAs($newFileName.'.docx');
            
            $zip_file = $newFileName.'.zip'; // Name of our archive to download

            // Initializing PHP class
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            
            $firstfile=$newFileName.'.docx';
            $calculate=$this->form5_calculate_word($id,$name).'.docx';
            $obosn=$this->form5_obosn_word($id,$name).'.docx';
            // Adding file: second parameter is what will the path inside of the archive
            // So it will create another folder called "storage/" inside ZIP, and put the file there.
            $zip->addFile($firstfile);
       
            $zip->addFile($calculate);
            $zip->addFile($obosn);
            $zip->close();
         
            unlink(public_path($firstfile));
            unlink(public_path($obosn));
            unlink(public_path($calculate));
          
                        
                return response()->download($zip_file)->deleteFileAfterSend();
        }
            
    }
    public function form5_obosn_word($id, $name)
    {
        $phpWord= new PhpWord();
            $grant_obosn=grant_obosn::where('project_id', $id)->get();
            $grant=Grant::find($id);
            $name_nir=$grant->workName;
            $goal=$grant_obosn->first()->goal;
            $idea=$grant_obosn->first()->idea;
            $struct=$grant_obosn->first()->struct;
            $state=$grant_obosn->first()->state;
            $rezults=$grant_obosn->first()->rezults;
            $field=$grant_obosn->first()->field;
            $info=$grant_obosn->first()->info;
            $templateProcessor= new TemplateProcessor('templates\form5_obosn.docx');
  
  
    $index=0;
    $templateProcessor->setValue('name_nir',$name_nir);
    $templateProcessor->setValue('goal',$goal);
    $templateProcessor->setValue('idea',$idea);
    $templateProcessor->setValue('struct',$struct);
    $templateProcessor->setValue('state',$state);
    $templateProcessor->setValue('rezults',$rezults);
    $templateProcessor->setValue('field',$field);
    $templateProcessor->setValue('info',$info);
    
    $section=$phpWord->addSection();
    $section->addTextBreak(1);
    $styleCell =
    array(
    'borderColor' =>'000000',
    'borderSize' => 3,
    'valign' => 'center',
    'cellMargin' => 100,
    );
    $styleText = array(
        'name' => 'Times New Roman',
        'valign'=>'center',
        'size' => 12,
    );

    

    
    
    $newFileName = $name.'_Обоснование_'.$id;
    $templateProcessor->saveAs($newFileName.'.docx');
    return $newFileName;
    }
    public function form5_calculate_word($id, $name)
    {
        $phpWord= new PhpWord();
            $grant_calculate=grant_calculate::where('project_id', $id)->get();
            $grant=Grant::find($id);
            $name_nir=$grant->workName;
            $pay=$grant_calculate->first()->pay;
            $materials=$grant_calculate->first()->materials;
            $accruals=$grant_calculate->first()->accruals;
            $business=$grant_calculate->first()->business;
            $invoices=$grant_calculate->first()->invoices;
            $costs=$grant_calculate->first()->costs;
            $sum=$grant_calculate->first()->sum;
            $templateProcessor= new TemplateProcessor('templates\form5_calculate.docx');
  
  
    $index=0;
    $templateProcessor->setValue('name_nir',$name_nir);
    $templateProcessor->setValue('pay',$pay);
    $templateProcessor->setValue('materials',$materials);
    $templateProcessor->setValue('accruals',$accruals);
    $templateProcessor->setValue('business',$business);
    $templateProcessor->setValue('invoices',$invoices);
    $templateProcessor->setValue('costs',$costs);
    $templateProcessor->setValue('sum',$sum);
    
    $section=$phpWord->addSection();
    $section->addTextBreak(1);
    $styleCell =
    array(
    'borderColor' =>'000000',
    'borderSize' => 3,
    'valign' => 'center',
    'cellMargin' => 100,
    );
    $styleText = array(
        'name' => 'Times New Roman',
        'valign'=>'center',
        'size' => 12,
    );

    

    
    
    $newFileName = $name.'_Калькуляция_'.$id;
    $templateProcessor->saveAs($newFileName.'.docx');
    return $newFileName;
    }
    public function form6_teo_word($id,$name){
        $rcpiteo=RcpiTeo::where("project_id",$id);
        $rcpi=Repconc::where("id",$id);
        $templateProcessor= new TemplateProcessor('templates\form6_teo.docx');
        $templateProcessor->setValue('nameProject',$rcpi->first()->nameProject);
        $templateProcessor->setValue('teoPotrProblem',$rcpiteo->first()->teoPotrProblem);
        $templateProcessor->setValue('teoDescripProd',$rcpiteo->first()->teoDescripProd);
        $templateProcessor->setValue('teoBizModel',$rcpiteo->first()->teoBizModel);
        $templateProcessor->setValue('teoRinokInf',$rcpiteo->first()->teoRinokInf);
        $templateProcessor->setValue('teoDescripTechn',$rcpiteo->first()->teoDescripTechn);
        $templateProcessor->setValue('teoConcurent',$rcpiteo->first()->teoConcurent);
        $templateProcessor->setValue('teoIntSobstv',$rcpiteo->first()->teoIntSobstv);
        $templateProcessor->setValue('teoTeamProject',$rcpiteo->first()->teoTeamProject);
        $templateProcessor->setValue('teoMarketing',$rcpiteo->first()->teoMarketing);
        $templateProcessor->setValue('teoFinIndic',$rcpiteo->first()->teoFinIndic);
        $templateProcessor->setValue('teoUnitEconomy',$rcpiteo->first()->teoUnitEconomy);
        $templateProcessor->setValue('teoInvestPerm',$rcpiteo->first()->teoInvestPerm);
        $templateProcessor->setValue('teoRiskProject',$rcpiteo->first()->teoRiskProject);
        $templateProcessor->setValue('teoRelizeTemp',$rcpiteo->first()->teoRelizeTemp);
        $newFileName=$name."_ТЭО";
        $templateProcessor->saveAs($newFileName.'.docx');
        return $newFileName;

    }

    public function form6_pass_word($checkboxes,$inputs,$name){

            $templateProcessor= new TemplateProcessor('templates\form6_pass.docx');
        for($i=0; $i<count($checkboxes); $i++){
            if($checkboxes[$i]->status==1){
            $templateProcessor->setValue("p{$i}",'☑');
            }
            else{
                $templateProcessor->setValue("p{$i}",'☐');
            }
        }
        $templateProcessor->setValue("pasNameProject",$inputs->first()->pasNameProject);
        $templateProcessor->setValue("pasKratkDescrip",$inputs->first()->pasKratkDescrip);
        $templateProcessor->setValue("pasRinokSbita",$inputs->first()->pasRinokSbita);

        $templateProcessor->setValue("pasGeneralPer",$inputs->first()->pasGeneralPer);
        $templateProcessor->setValue("pasRealizationTemp",$inputs->first()->pasRealizationTemp);
        $templateProcessor->setValue("pasObjectComerc",$inputs->first()->pasObjectComerc);
        $templateProcessor->setValue("pasDoztizhProject",$inputs->first()->pasDoztizhProject);
        $templateProcessor->setValue("pasDopInformation",$inputs->first()->pasDopInformation);
        $templateProcessor->setValue("pasDescription",$inputs->first()->pasDescription);
        $templateProcessor->setValue("pasOtherSphere",$inputs->first()->pasOtherSphere);

            $newFileName=$name."_Пасспорт ИП";
            $templateProcessor->saveAs($newFileName.'.docx');
            return $newFileName;
    
}
 
    public function form6_bp_word($id,$name){
        $phpWord= new PhpWord();
        $rcpi_bp=RcpiBp::where('project_id',$id)->get();
        $templateProcessor= new TemplateProcessor('templates\form6_bp.docx');
        $templateProcessor->setValue("bpFio",$rcpi_bp->first()->bpFio);
        $templateProcessor->setValue("bpSoderzh",$rcpi_bp->first()->bpSoderzh);
        $templateProcessor->setValue("bpResume",$rcpi_bp->first()->bpResume);
        $templateProcessor->setValue("bpProblem",$rcpi_bp->first()->bpProblem);
        $templateProcessor->setValue("bpProduct",$rcpi_bp->first()->bpProduct);
        $templateProcessor->setValue("bpAnalize",$rcpi_bp->first()->bpAnalize);
        $templateProcessor->setValue("bpSobstv",$rcpi_bp->first()->bpSobstv);
        $templateProcessor->setValue("bpPotreb",$rcpi_bp->first()->bpPotreb);
        $templateProcessor->setValue("bpPrice",$rcpi_bp->first()->bpPrice);
        $templateProcessor->setValue("bpConcurents",$rcpi_bp->first()->bpConcurents);
        $templateProcessor->setValue("bpSuppliers",$rcpi_bp->first()->bpSuppliers);
        $templateProcessor->setValue("bpProizPlan",$rcpi_bp->first()->bpProizPlan);
        $templateProcessor->setValue("bpOrgPlan",$rcpi_bp->first()->bpOrgPlan);
        $templateProcessor->setValue("bpRelizeProblems",$rcpi_bp->first()->bpRelizeProblems);
        $templateProcessor->setValue("bpFinPlan",$rcpi_bp->first()->bpFinPlan);
        $templateProcessor->setValue("bpInformation",$rcpi_bp->first()->bpInformation);
        $newFileName=$name."_БизнесПлан";
        $templateProcessor->saveAs($newFileName.'.docx');
        return $newFileName;




    }
    public function form4_plan_word($id, $name)
    {
        $phpWord= new PhpWord();
            $gpni_plan=Gpni_plan::where('project_id', $id)->get();
            
           
            $direction=$gpni_plan->first()->direction;
            $Carryingout=$gpni_plan->first()->Carryingout;
            $nameingeneral=$gpni_plan->first()->nameingeneral;
            $nachPlanneddates=$gpni_plan->first()->nachPlanneddates;
            $endPlanneddates=$gpni_plan->first()->endPlanneddates;
            $totalcost=$gpni_plan->first()->totalcost;
            $results=$gpni_plan->first()->results;
            $name1p=$gpni_plan->first()->name1p;
            $nachPlanneddates1p=$gpni_plan->first()->nachPlanneddates1p;
            $endPlanneddates1p=$gpni_plan->first()->endPlanneddates1p;
            $totalcost1p=$gpni_plan->first()->totalcost1p;
            $results1p=$gpni_plan->first()->results1p;
            $name2p=$gpni_plan->first()->name2p;
            $nachPlanneddates2p=$gpni_plan->first()->nachPlanneddates2p;
            $endPlanneddates2p=$gpni_plan->first()->endPlanneddates2p;
            $totalcost2p=$gpni_plan->first()->totalcost2p;
            $results2p=$gpni_plan->first()->results2p;
            
            $templateProcessor= new TemplateProcessor('templates\form4_plan.docx');
  
  
    $index=0;
    $templateProcessor->setValue('direction',$direction);
    $templateProcessor->setValue('Carryingout',$Carryingout);
    $templateProcessor->setValue('nameingeneral',$nameingeneral);
    $templateProcessor->setValue('nachPlanneddates',$nachPlanneddates);
    $templateProcessor->setValue('endPlanneddates',$endPlanneddates);
    $templateProcessor->setValue('totalcost',$totalcost);
    $templateProcessor->setValue('results',$results);
    $templateProcessor->setValue('name1p',$name1p);
    $templateProcessor->setValue('nachPlanneddates1p',$nachPlanneddates1p);
    $templateProcessor->setValue('endPlanneddates1p',$endPlanneddates1p);
    $templateProcessor->setValue('totalcost1p',$totalcost1p);
    $templateProcessor->setValue('results1p',$results1p);
    $templateProcessor->setValue('name2p',$name2p);
    $templateProcessor->setValue('nachPlanneddates2p',$nachPlanneddates2p);
    $templateProcessor->setValue('endPlanneddates2p',$endPlanneddates2p);
    $templateProcessor->setValue('totalcost2p',$totalcost2p);
    $templateProcessor->setValue('results2p',$results2p);
    $section=$phpWord->addSection();
    $section->addTextBreak(1);
    $styleCell =
    array(
    'borderColor' =>'000000',
    'borderSize' => 3,
    'valign' => 'center',
    'cellMargin' => 100,
    );
    $styleText = array(
        'name' => 'Times New Roman',
        'valign'=>'center',
        'size' => 12,
    );

    

    
    
    $newFileName = $name.'_Календарный план_'.$id;
    $templateProcessor->saveAs($newFileName.'.docx');
    return $newFileName;
    }
    public function form4_obosn_word($id, $name)
    {
        $phpWord= new PhpWord();
            $gpni_obosn=Gpni_obosn::where('project_id', $id)->get();
            $gpni=Gpni::find($id);

            $nameN=$gpni->nameN;
            $nameP=$gpni->nameP;
            $number=$gpni->number;
            $name_nir=$gpni_obosn->first()->name_nir;
            $goals_nir=$gpni_obosn->first()->goals_nir;
            $relevance_nir=$gpni_obosn->first()->relevance_nir;
            $results_nir=$gpni_obosn->first()->results_nir;
            $plan_results_nir=$gpni_obosn->first()->plan_results_nir;
            $volume_nir=$gpni_obosn->first()->volume_nir;
       
           
            
            $templateProcessor= new TemplateProcessor('templates\form4_obosn.docx');
  
  
    $index=0;
    $templateProcessor->setValue('nameN',$nameN);
    $templateProcessor->setValue('nameP',$nameP);
    $templateProcessor->setValue('number',$number);
    $templateProcessor->setValue('name_nir',$name_nir);
    $templateProcessor->setValue('goals_nir',$goals_nir);
    $templateProcessor->setValue('relevance_nir',$relevance_nir);
    $templateProcessor->setValue('results_nir',$results_nir);
    $templateProcessor->setValue('plan_results_nir',$plan_results_nir);
    $templateProcessor->setValue('volume_nir',$volume_nir);

    $section=$phpWord->addSection();
    $section->addTextBreak(1);
    $styleCell =
    array(
    'borderColor' =>'000000',
    'borderSize' => 3,
    'valign' => 'center',
    'cellMargin' => 100,
    );
    $styleText = array(
        'name' => 'Times New Roman',
        'valign'=>'center',
        'size' => 12,
    );

    

    
    
    $newFileName = $name.'_Обоснование_'.$id;
    $templateProcessor->saveAs($newFileName.'.docx');
    return $newFileName;
    }
    public function form4_plan_calculate($id, $name)
    {
        $phpWord= new PhpWord();
            $gpni_calculate=Gpni_calculate::where('project_id', $id)->get();
            $gpni=Gpni::find($id);
            $nameN=$gpni->nameN;
            $nameP=$gpni->nameP;
            $totalCalculate1=$gpni_calculate->first()->totalCalculate1;
            $totalCalculate2=$gpni_calculate->first()->totalCalculate2;
            $totalCalculate3=$gpni_calculate->first()->totalCalculate3;
            $totalCalculate4=$gpni_calculate->first()->totalCalculate4;
            $totalCalculate5=$gpni_calculate->first()->totalCalculate5;
            $totalCalculate6=$gpni_calculate->first()->totalCalculate6;
            $totalCalculate7=$gpni_calculate->first()->totalCalculate7;
            $totalCalculate8=$gpni_calculate->first()->totalCalculate8;
            $firstCalculate1=$gpni_calculate->first()->firstCalculate1;
            $firstCalculate2=$gpni_calculate->first()->firstCalculate2;
            $firstCalculate3=$gpni_calculate->first()->firstCalculate3;
            $firstCalculate4=$gpni_calculate->first()->firstCalculate4;
            $firstCalculate5=$gpni_calculate->first()->firstCalculate5;
            $firstCalculate6=$gpni_calculate->first()->firstCalculate6;
            $firstCalculate7=$gpni_calculate->first()->firstCalculate7;
            $firstCalculate8=$gpni_calculate->first()->firstCalculate8;
            $totalCalculateSum=$gpni_calculate->first()->totalCalculateSum;
            $firstCalculateSum=$gpni_calculate->first()->firstCalculateSum;
            $templateProcessor= new TemplateProcessor('templates\form4_calculate.docx');
  
  
    $index=0;
    $templateProcessor->setValue('nameN',$nameN);
    $templateProcessor->setValue('nameP',$nameP);
    $templateProcessor->setValue('totalCalculate1',$totalCalculate1);
    $templateProcessor->setValue('totalCalculate2',$totalCalculate2);
    $templateProcessor->setValue('totalCalculate3',$totalCalculate3);
    $templateProcessor->setValue('totalCalculate4',$totalCalculate4);
    $templateProcessor->setValue('totalCalculate5',$totalCalculate5);
    $templateProcessor->setValue('totalCalculate6',$totalCalculate6);
    $templateProcessor->setValue('totalCalculate7',$totalCalculate7);
    $templateProcessor->setValue('totalCalculate8',$totalCalculate8);
    $templateProcessor->setValue('firstCalculate1',$firstCalculate1);
    $templateProcessor->setValue('firstCalculate2',$firstCalculate2);
    $templateProcessor->setValue('firstCalculate3',$firstCalculate3);
    $templateProcessor->setValue('firstCalculate4',$firstCalculate4);
    $templateProcessor->setValue('firstCalculate5',$firstCalculate5);
    $templateProcessor->setValue('firstCalculate6',$firstCalculate6);
    $templateProcessor->setValue('firstCalculate7',$firstCalculate7);
    $templateProcessor->setValue('firstCalculate8',$firstCalculate8);
    $templateProcessor->setValue('totalCalculateSum',$totalCalculateSum);
    $templateProcessor->setValue('firstCalculateSum',$firstCalculateSum);
    $section=$phpWord->addSection();
    $section->addTextBreak(1);
    $styleCell =
    array(
    'borderColor' =>'000000',
    'borderSize' => 3,
    'valign' => 'center',
    'cellMargin' => 100,
    );
    $styleText = array(
        'name' => 'Times New Roman',
        'valign'=>'center',
        'size' => 12,
    );

    $newFileName = $name.'_Калькуляция_'.$id;
    $templateProcessor->saveAs($newFileName.'.docx');
    return $newFileName;
    }
    public function  form_pdf($name,$id)
    {
        if($name== "Молодежные инициативы"){
        $phpWord= new PhpWord();
    
    $phpWord->setDefaultFontName('Times New Roman');
    $phpWord->setDefaultFontSize(14);
    $molIndic=MolIndic::where('project_id', $id)->get();
    $molInic=MolInic::find($id);
 
  
    $templateProcessor= new TemplateProcessor('templates\form1.docx');
  
    $templateProcessor->deleteBlock('tableRow');
    $index=0;
    $templateProcessor->setValue('projectName',$molInic->nameProject);
    $templateProcessor->setValue('regionName',$molInic->nameRegion);
    $templateProcessor->setValue('locality',$molInic->namePunct);
    $templateProcessor->setValue('description',$molInic->descriptionProblem);
    $templateProcessor->setValue('realizationTemp',$molInic->realizationTemp);
    $templateProcessor->setValue('fioRuk',$molInic->fioRuk);
    $templateProcessor->setValue('phone',$molInic->phone);
    $templateProcessor->setValue('email',$molInic->email);
    $templateProcessor->setValue('sostav',$molInic->sostav);
    $templateProcessor->setValue('dopInformation',$molInic->dopInformation);
    $indicator = array();
    foreach($molIndic as $mol)
    {
        array_push($indicator,$mol->indicator);
    }
    $valueIndicator = array();
    foreach($molIndic as $mol)
    {
        array_push($valueIndicator,$mol->value);
    }
    
    
    $templateProcessor->setValue('indicator',$indicator[0]);
    
    
    $section = $phpWord->addSection();
    $header = array('size' => 16, 'bold' => true);


    // 2. Advanced table

    $section->addTextBreak(1);
    $styleCell =
    array(
    'borderColor' =>'000000',
    'borderSize' => 5,
    'valign' => 'center',
    );
    $styleText = array(
        'name' => 'Times New Roman',
        'size' => 14, // Размер шрифта в точках
    );

    $table = $section->addTable($styleCell);
    $table->addRow(900);
    $table->addCell(800, $styleCell)->addText('№ п/п',$styleText);
    $table->addCell(4800, $styleCell)->addText('Показатель, единиц измерения',$styleText);
    $table->addCell(4000, $styleCell)->addText('Значение показателя',$styleText);
    for ($i = 0; $i < count($indicator); $i++) {
        $table->addRow();
        $table->addCell(800)->addText($i+1,$styleText);
        $table->addCell(4800)->addText($indicator[$i],$styleText);      
        $table->addCell(4000)->addText($valueIndicator[$i],$styleText);
    };
    
   

        $templateProcessor->setComplexBlock('mainTable',$table);
    $newFileName = time();
   
    $templateProcessor->saveAs($newFileName.'.docx');
    
    $filePath = public_path($newFileName.'.docx');
    
    $word1 = IOFactory::load($newFileName.'.docx', 'Word2007');

   
    
    
    // Конвертируем документ в формат PDF
    $dompdf = base_path('vendor/dompdf/dompdf');
    \PhpOffice\PhpWord\Settings::setPdfRendererPath($dompdf);
    \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
    $pdfwriter=\PhpOffice\PhpWord\IOFactory::createWriter($word1,'PDF');
    $fontDir = base_path('fonts');
    $options = new \Dompdf\Options();
    $options->set('fontDir', $fontDir);
    $pdfwriter->save(public_path($newFileName));
    header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $newFileName.'.pdf');
readfile(public_path($newFileName));
unlink(public_path($newFileName));
unlink($filePath);
    // Отправляем файл на скачивание


   }
   if($name=="РКИП"){
    $phpWord=new PhpWord();
    $phpWord->setDefaultFontName('Times New Roman');
    $phpWord->setDefaultFontSize(14);
    $repconc=Repconc::find($id);
    $templateProcessor= new TemplateProcessor('templates\form6.docx');
    $templateProcessor->setValue('nominationName',$repconc->nominationName);
    $templateProcessor->setValue('nameProject',$repconc->nameProject);
    $templateProcessor->setValue('fio',$repconc->fio);
    $templateProcessor->setValue('teachWorkPlace',$repconc->teachWorkPlace);
    $templateProcessor->setValue('dolzhnUch',$repconc->dolzhnUch);
    $templateProcessor->setValue('uchStep',$repconc->uchStep);
    $templateProcessor->setValue('adress',$repconc->adress);
    $templateProcessor->setValue('phone',$repconc->phone);
    $templateProcessor->setValue('email',$repconc->email);
    $templateProcessor->setValue('projectLink',$repconc->projectLink);
    $templateProcessor->setValue('yurName',$repconc->yurName);
    $templateProcessor->setValue('fioRuk',$repconc->fioRuk);
    $templateProcessor->setValue('dolzhnYur',$repconc->dolzhnYur);
    $templateProcessor->setValue('yurStep',$repconc->yurStep);
    $templateProcessor->setValue('yurAdress',$repconc->yurAdress);
    $templateProcessor->setValue('platNumber',$repconc->platNumber);
    $templateProcessor->setValue('yurPhone',$repconc->yurPhone);
    $templateProcessor->setValue('yurEmail',$repconc->yurEmail);
    $templateProcessor->setValue('fioCommand',$repconc->fioCommand);
    $templateProcessor->setValue('yurLink',$repconc->yurLink);
    $newFileName = $name."_".$id;
   
    $templateProcessor->saveAs($newFileName.'.docx');
    $filePath = public_path($newFileName.'.docx');
    $word1 = IOFactory::load($newFileName.'.docx', 'Word2007');

   
    
    
    // Конвертируем документ в формат PDF
    $dompdf = base_path('vendor/dompdf/dompdf');
    \PhpOffice\PhpWord\Settings::setPdfRendererPath($dompdf);
    \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
    $pdfwriter=\PhpOffice\PhpWord\IOFactory::createWriter($word1,'PDF');
    $fontDir = base_path('fonts');
    $options = new \Dompdf\Options();
    $options->set('fontDir', $fontDir);
    $pdfwriter->save(public_path($newFileName));
    header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $newFileName.'.pdf');
readfile(public_path($newFileName));
unlink(public_path($newFileName));
unlink($filePath);
  

}
    if ($name=="Участие в НИР"){
        $phpWord= new PhpWord();
    
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);
        $barsunirdop=BarsuNirDop::where('project_id', $id)->get();
        $barsunir= BarsuNir::find($id);
        $templateProcessor= new TemplateProcessor('templates\form2.docx');
  
        $templateProcessor->deleteBlock('tableRow');
        $index=0;
        $templateProcessor->setValue('sinceDir',$barsunir->sinceDir);
    
        $templateProcessor->setValue('workTheme',$barsunir->workTheme);
        $templateProcessor->setValue('sinceElem',$barsunir->sinceElem);
        $templateProcessor->setValue('nirRuks',$barsunir->nirRuks);
        $templateProcessor->setValue('realizationTemp',$barsunir->realizationTemp);
        $templateProcessor->setValue('phone',$barsunir->phone);
        $templateProcessor->setValue('obosnovanie',$barsunir->obosnovanie);
        $templateProcessor->setValue('goalsNir',$barsunir->goalsNir);
        $templateProcessor->setValue('ozhidResult',$barsunir->ozhidResult);
        $templateProcessor->setValue('praktZnach',$barsunir->praktZnach);
        $section=$phpWord->addSection();
    

    $newFileName = time();
    $section->addTextBreak(1);
    $styleCell =
    array(
    'borderColor' =>'000000',
    'borderSize' => 3,
    'valign' => 'center',
    );
    $styleText = array(
        'name' => 'Times New Roman',
        'valign'=>'center',
        'size' => 14,
    );

    $table = $section->addTable($styleCell);
    $workEtap=array();
    $nachSrok=array();
    $endSrok=array();
    $kontrResult=array();
    foreach($barsunirdop as $item)
    {
        array_push($workEtap,$item->workEtap);
        array_push($nachSrok,$item->nachSrok);
        array_push($endSrok,$item->endSrok);
        array_push($kontrResult,$item->kontrResult);
    }

    
    for ($i = 0; $i < count($workEtap); $i++) {

    $table->addRow(200);
    $table->addCell(1090, $styleCell)->addText($i,$styleText);
    $table->addCell(3588, $styleCell)->addText($workEtap[$i],$styleText);
    $table->addCell(1385, $styleCell)->addText($nachSrok[$i],$styleText);
    $table->addCell(1470, $styleCell)->addText($endSrok[$i],$styleText);
    $table->addCell(1900, $styleCell)->addText($kontrResult[$i],$styleText);

    }
   
    $templateProcessor->setComplexBlock('table',$table);
    $templateProcessor->setComplexBlock('table',$table);
    $templateProcessor->saveAs($newFileName.'.docx');
    
    $filePath = public_path($newFileName.'.docx');
    
    $word1 = IOFactory::load($newFileName.'.docx', 'Word2007');

   
    
    
    // Конвертируем документ в формат PDF
    $dompdf = base_path('vendor/dompdf/dompdf');
    \PhpOffice\PhpWord\Settings::setPdfRendererPath($dompdf);
    \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
    $pdfwriter=\PhpOffice\PhpWord\IOFactory::createWriter($word1,'PDF');
    $fontDir = base_path('fonts');
    $options = new \Dompdf\Options();
    $options->set('fontDir', $fontDir);
    $pdfwriter->save(public_path($newFileName));
    header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $newFileName.'.pdf');
readfile(public_path($newFileName));
unlink(public_path($newFileName));
unlink($filePath);

    }


    if($name=="100 ИДЕЙ ДЛЯ БЕЛАРУСИ"){
        $phpWord= new PhpWord();
        $hundreadideas=HudredIdeas::find($id);
        $name_project = $hundreadideas->name_project;
        $name_autors = $hundreadideas->name_autors;
        $relevance = $hundreadideas->relevance;
        $goals_objectives = $hundreadideas->goals_objectives;
        $advantages_project = $hundreadideas->advantages_project;
        $property_protection = $hundreadideas->property_protection;
        $offers = $hundreadideas->offers;
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(14);
    
    
      
        $templateProcessor= new TemplateProcessor('templates\form3.docx');
      
        $templateProcessor->deleteBlock('tableRow');
        $index=0;
        $templateProcessor->setValue('name_project',$name_project);
        $templateProcessor->setValue('name_autors',$name_autors);
        $templateProcessor->setValue('relevance',$relevance);
        $templateProcessor->setValue('goals_objectives',$goals_objectives);
        $templateProcessor->setValue('advantages_project',$advantages_project);
        $templateProcessor->setValue('property_protection',$property_protection);
        $templateProcessor->setValue('offers',$offers);
        $newFileName = $name.'_'.$id;
        $templateProcessor->saveAs($newFileName.'.docx');
        
    $filePath = public_path($newFileName.'.docx');
    
    $word1 = IOFactory::load($newFileName.'.docx', 'Word2007');

   
    
    
    // Конвертируем документ в формат PDF
    $dompdf = base_path('vendor/dompdf/dompdf');
    \PhpOffice\PhpWord\Settings::setPdfRendererPath($dompdf);
    \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
    $pdfwriter=\PhpOffice\PhpWord\IOFactory::createWriter($word1,'PDF');
    $fontDir = base_path('fonts');
    $options = new \Dompdf\Options();
    $options->set('fontDir', $fontDir);
    $pdfwriter->save(public_path($newFileName));
    header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $newFileName.'.pdf');
readfile(public_path($newFileName));
unlink(public_path($newFileName));
unlink($filePath);

    }
    if($name=="ГПНИ"){
        $phpWord= new PhpWord();
        $gpni=Gpni::find($id);
        $sinceDir= $gpni->sincedir;
        $namePr= $gpni->namePr;
        $orgZav= $gpni->orgZav;
        $nach= $gpni->nach;
        $end= $gpni->end;
        $allCost= $gpni->allCost;
        $fin1= $gpni->fin1;
        $fin2= $gpni->fin2;
        $fin3= $gpni->fin3;
        $gpnidop=GpniDop::where('project_id', $id)->get();
        $fio=array();
        $uchStep=array();
        $uchZav=array();
        $kafLab=array();
        $phone=array();
        $email=array();
        foreach($gpnidop as $item)
        {
            array_push($fio,$item->fio);
            array_push($uchStep, $item->uchStep);
            array_push($uchZav,$item->uchZav);
            array_push($kafLab,$item->kafLab);
            array_push($phone,$item->phone);
            array_push($email,$item->email);
        }
        $templateProcessor= new TemplateProcessor('templates\form4.docx');


$index=0;
$templateProcessor->setValue('sinceDir',$sinceDir);
$templateProcessor->setValue('nach',$nach);
$templateProcessor->setValue('namePr',$namePr);
$templateProcessor->setValue('orgZav',$orgZav);
$templateProcessor->setValue('end',$end);
$templateProcessor->setValue('allCost',$allCost);
$templateProcessor->setValue('fin1',$fin1);
$templateProcessor->setValue('fin2',$fin2);
$templateProcessor->setValue('fin3',$fin3);
$newFileName = time();


$section=$phpWord->addSection();



$section->addTextBreak(1);
$styleCell =
array(
'borderColor' =>'000000',
'borderSize' => 3,
'valign' => 'center',
'cellMargin' => 100,
);
$styleText = array(
    'name' => 'Times New Roman',
    'valign'=>'center',
    'size' => 12,
);

$table = $section->addTable($styleCell);
for ($i = 0; $i < count($fio); $i++) {
    $section->addTextBreak();
    $table->addRow(200);
$table->addCell(5850, $styleCell)->addText("Ф.И.О. (полное) ",$styleText);
$table->addCell(4400, $styleCell)->addText($fio[$i],$styleText);
$table->addRow(200);
$table->addCell(5850, $styleCell)->addText("Ученая степень ",$styleText);
$table->addCell(4400, $styleCell)->addText($uchStep[$i],$styleText);
$table->addRow(200);
$table->addCell(5850, $styleCell)->addText("Ученое звание ",$styleText);
$table->addCell(4400, $styleCell)->addText($uchZav[$i],$styleText);
$table->addRow(200);
$table->addCell(5850, $styleCell)->addText("Кафедра, лаборатория",$styleText);
$table->addCell(4400, $styleCell)->addText($kafLab[$i],$styleText);
$table->addRow(200);
$table->addCell(5850, $styleCell)->addText("Телефон служебный (с кодом города), мобильный с указанием кода оператора",$styleText);
$table->addCell(4400, $styleCell)->addText($phone[$i],$styleText);
$table->addRow(200);
$table->addCell(5850, $styleCell)->addText("Контактный e-mail",$styleText);
$table->addCell(4400, $styleCell)->addText($email[$i],$styleText);




}
$newFileName = $name.'_'.$id;
$templateProcessor->setComplexBlock('table',$table);
$templateProcessor->saveAs($newFileName.'.docx');
$filePath = public_path($newFileName.'.docx');
    
    $word1 = IOFactory::load($newFileName.'.docx', 'Word2007');

   
    
    
    // Конвертируем документ в формат PDF
    $dompdf = base_path('vendor/dompdf/dompdf');
    \PhpOffice\PhpWord\Settings::setPdfRendererPath($dompdf);
    \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
    $pdfwriter=\PhpOffice\PhpWord\IOFactory::createWriter($word1,'PDF');
    $fontDir = base_path('fonts');
    $options = new \Dompdf\Options();
    $options->set('fontDir', $fontDir);
    $pdfwriter->save(public_path($newFileName));
    header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $newFileName.'.pdf');
readfile(public_path($newFileName));
unlink(public_path($newFileName));
unlink($filePath);


    }
    if($name=="Заявка на получение гранта"){
        $phpWord= new PhpWord();
        $grant=Grant::find($id);
        $scienceDirection=$grant->sienceDirection;
        $fioGrad=$grant->fioGrad;
        $currentYear=date("Y");
        $grandCategory=$grant->grandCategory;
        $workName=$grant->workName;
        $disertationTheme=$grant->disertationTheme;
        $uchrName=$grant->uchrName;
        $special=$grant->special;
        $knowledge=$grant->knowledge;
        $templateProcessor= new TemplateProcessor('templates\form5.docx');
        $templateProcessor->setValue('scienceDirection',$scienceDirection);
        $templateProcessor->setValue('fioGrad',$fioGrad);
        $templateProcessor->setValue('grandCategory',$grandCategory);
        $templateProcessor->setValue('workName',$workName);
        $templateProcessor->setValue('disertationTheme',$disertationTheme);
        $templateProcessor->setValue('uchrName',$uchrName);
        $templateProcessor->setValue('special',$special);
        $templateProcessor->setValue('knowledge',$knowledge);
        $templateProcessor->setValue('currentYear',$currentYear);
        $newFileName = $name.'_'.$id;
        $templateProcessor->saveAs($newFileName.'.docx');
        $filePath = public_path($newFileName.'.docx');
    
    $word1 = IOFactory::load($newFileName.'.docx', 'Word2007');

   
    
    
    // Конвертируем документ в формат PDF
    $dompdf = base_path('vendor/dompdf/dompdf');
    \PhpOffice\PhpWord\Settings::setPdfRendererPath($dompdf);
    \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
    $pdfwriter=\PhpOffice\PhpWord\IOFactory::createWriter($word1,'PDF');
    $fontDir = base_path('fonts');
    $options = new \Dompdf\Options();
    $options->set('fontDir', $fontDir);
    $pdfwriter->save(public_path($newFileName));
    header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $newFileName.'.pdf');
readfile(public_path($newFileName));
unlink(public_path($newFileName));
unlink($filePath);
    }


    }

    public function form6_strategy_word($checkboxes,$inputs,$name,$rcpi){
        $templateProcessor= new TemplateProcessor('templates\form6_strategy.docx');
        for($i=0; $i<count($checkboxes);$i++){
            if($checkboxes[$i]->status==1){
            $templateProcessor->setValue("s{$i}",'☑');
            }
            else{
                $templateProcessor->setValue("s{$i}",'☐');
            }
        }
        $templateProcessor->setValue("nameProject",$rcpi->nameProject);
        $templateProcessor->setValue("nominationName",$rcpi->nominationName);
        $templateProcessor->setValue("sFio",$inputs->first()->sFio);
        $templateProcessor->setValue("sOtherSbosob",$inputs->first()->sOtherSbosob);
        $templateProcessor->setValue("sDescriptKomerc",$inputs->first()->sDescriptKomerc);
        $templateProcessor->setValue("sStratComerc",$inputs->first()->sStratComerc);
        $templateProcessor->setValue("currentYear",date("Y"));
        $templateProcessor->setValue("nextYear",date("Y",strtotime('+1 year')));
        $newFileName=$name."_Стратегия";
        $templateProcessor->saveAs($newFileName.'.docx');
        return $newFileName;
    }
    

    public function back(){
        return redirect('/cabinet');
    }
    public function form2(){
        return view('forms/form2');
    }
    public function form3(){
        return view('forms/form3');
    }
    public function form6(){
        return view('forms/form6');
    }
    public function form4(){
        return view('forms/form4');
    }
    public function form5(){
        return view('forms/form5');
    }
    public function login(){
        return view('forms/login');
    }
    public function registerPage(){
        return view('register');
    }
    public function cabinet(){

        if(Auth::user()->Role=='User'){
        $molInics = MolInic::select('name', 'created_at','id','owner')->where('user_id', auth()->id());
        $repconc = Repconc::select('name', 'created_at','id','owner')->where('user_id', auth()->id());
        $barsunirs = BarsuNir::select('name', 'created_at','id','owner')->where('user_id', auth()->id());
        $hundredideas= HudredIdeas::select('name', 'created_at','id','owner')->where('user_id', auth()->id());
        $gpnis=Gpni::select('name','created_at','id','owner')->where('user_id', auth()->id());
        $grant=Grant::select('name','created_at','id','owner')->where('user_id', auth()->id());
        $items = $molInics->union($barsunirs)->union($hundredideas)->union($gpnis)->orderBy('created_at', 'desc')->union($grant)->union($repconc)->orderBy('created_at', 'desc')->paginate(7);
        $notifications = auth()->user()->unreadNotifications;
        return view('cabinet', compact('items'), ['notifications' => $notifications]);
      
       
        }
        
        if(Auth::user()->Role=='Admin'){
            $molInics = MolInic::select('name', 'created_at','id','owner');
            $repconc = Repconc::select('name', 'created_at','id','owner');
            $barsunirs = BarsuNir::select('name', 'created_at','id','owner');
            $hundredideas= HudredIdeas::select('name', 'created_at','id','owner');
            $gpnis=Gpni::select('name','created_at','id','owner');
            $grant=Grant::select('name','created_at','id','owner');
            $items = $molInics->union($barsunirs)->union($hundredideas)->union($gpnis)->orderBy('created_at', 'desc')->union($grant)->union($repconc)->paginate(7);
            return view('cabinet', compact('items'));
           
            }
            
            

    }
    
}
