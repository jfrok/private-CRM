<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Project;
use App\Models\Site;
use App\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SiteController extends Controller
{
    public function index()
    {
        $siteProjects = Site::orderBy('date', 'DESC')->get();
        $customers = Customer::orderBy('company_name')->get();
        $projects = Project::where('status','Afgerond')->orderBy('title')->get();

        return view('crm-site.index', compact('siteProjects', 'customers', 'projects'));
    }

    public function showDetails()
    {
        return view('crm-site.details');
    }

    public function getProjects()
    {
        $siteProjects = Site::orderBy('date', 'DESC')->get();
        //$siteProjects = Site::Join('customers','sites.customers_id','=','customers.id')->get();
//dd($siteProjects);
        $data = view('crm-site.includes.table', compact('siteProjects'))->render();
        return response()->json($data);
    }

    public function getProjectsContent($siteId)
    {
        $contents = SiteContent::where('site_id', $siteId)->orderBy('sort', 'DESC')->get();
        //$siteProjects = Site::Join('customers','sites.customers_id','=','customers.id')->get();
//dd($siteProjects);
        $data = view('crm-site.includes.table-content', compact('contents'))->render();
        return response()->json($data);
    }

    public function saveCreate(Request $request)
    {
        $arr = unserialize($request->project);

        $project = $arr[0];
        $customer = $arr[1];
        $title_top = $request->title_top;
        $date_top = $request->date_top;
        $type_top = $request->type_top;
        $imageName = $request->file('thumbnail')?->getClientOriginalName();
        $slug_url = strtolower($title_top);

        $slug_url = '' . str_replace(' ', '-', $slug_url);


//        $dateFormat = Carbon::createFromFormat('m/d/Y', $date_top);
//        $monthName = $dateFormat->format('F');

        $site = new Site();
        $site->customer_id = $customer;
        $site->title = $title_top;
        $site->slug = $slug_url;
        $site->date = $date_top;
        $site->type = $type_top;
        $site->project_id = $project;
        $site->thumbnail = '/img/cases/' . $imageName;

        $site->save();
        $request->thumbnail?->move(public_path('/img/cases'), $imageName);


        return response()->json('success');
    }

    public function edit(Request $request)
    {
        $selectedCase = Site::find($_GET['caseId']);
        $customer = Customer::orderBy('company_name')->get();
        $projects = Project::where('status','Afgerond')->orderBy('title')->get();


        $data = view('crm-site.includes.edit-modal', compact('selectedCase', 'customer', 'projects'))->render();
        return response()->json($data);
    }

    public function saveEdit($caseId, Request $request)
    {


        $editproject = $request->editproject;
        $edittitle_top = $request->edit_title_top;
        $editdate_top = $request->edit_date_top;
        $edittype_top = $request->edit_type_top;
        //  $editthumbnail = $request->edit_thumbnail;
        //  $imageNameEdit = $editthumbnail->getClientOriginalName();
        if($request->file('edit_thumbnail')?->getClientOriginalName() == null){
            $imageNameEdit = $request->edit_thumbnail_path;


        }else{
            $imageNameEdit = $request->file('edit_thumbnail')?->getClientOriginalName();
        }
       // $imageNameEdit = $request->file('edit_thumbnail')->getClientOriginalName();

        //   dd($imageNameEdit);
        $site = Site::find($caseId);
        $site->project_id = $editproject;
        $site->title = $edittitle_top;
        $site->date = $editdate_top;
        $site->type = $edittype_top;
        //   $site->thumbnail = $editthumbnail;
        $request->file('edit_thumbnail')?->getClientOriginalName() == null ? $site->thumbnail = $imageNameEdit : $site->thumbnail = '/img/cases/' . $imageNameEdit;

$site->save();
        $request->file('edit_thumbnail')?->move(public_path('/img/cases'), $imageNameEdit);

        return response()->json('Case is aangepast!');
    }

    public function deleteCase($caseId)
    {
        Site::find($caseId)->delete();
    }

    public function deleteCaseContent($cId)
    {
        SiteContent::find($cId)->delete();
    }

    public function showContent($siteId)
    {
        $contents = SiteContent::where('site_id', $siteId)->orderBy('sort')->get();
        return view('crm-site.content', compact('contents', 'siteId'));
    }

////////////////////Content Edit////////////////////////////////////
    public function editContent(Request $request)
    {
        $selectedCaseContent = SiteContent::find($_GET['cId']);
        // $contents = SiteContent::orderBy('description')->get();

        $data = view('crm-site.includes.edit-content-modal', compact('selectedCaseContent',))->render();
        return response()->json($data);
    }

    public function saveEditContent($cid, Request $request)
    {
        // $imageNameEdit = $request->edit_image_path->getClientOriginalName();



        $editTitle = $request->edit_title;
        $editDescription = $request->edit_description;
        // $imageNameEdit = null;

        if($request->file('edit_image_path')?->getClientOriginalName() == null){
              $imageNameEdit = $request->file('edit_image_path');


        }else{
            $imageNameEdit = $request->file('edit_image_path')->getClientOriginalName();
        }

        $editImg = $request->file('edit_image_path');

        $site_content = SiteContent::find($cid);
        $site_content->description = $editDescription;
        $site_content->title = $editTitle;
        $site_content->image_path = '/img/cases/' . $imageNameEdit;


        $site_content->save();
          $request->file('edit_image_path')?->move(public_path('/img/cases'), $imageNameEdit);

        return response()->json('success');


    }
    /////////////////////////////////////////////////////////////////
    ///
    public function editSort()
    {
        foreach ($_GET['contentIds'] as $key => $id) {
            $content = SiteContent::find($id);
            $content->sort = $key;
            $content->save();
        }

        return response()->json('successs');
    }

    public function showResulat()
    {
        $showResulat = Site::orderBy('date', 'DESC')->get();
        $showResulatContent = SiteContent::orderBy('id', 'DESC')->get();

        //$showResulat = SiteContent::where('site_id', 'DESC')->get();
        return view('crm-site.show', compact('showResulat', 'showResulatContent'));
    }


    ///////////////////////////////Uplaod////////////////////////////
    public function saveContentText($siteId, Request $request)
    {
        $title = $request->title;
        $type = $request->type;
        $description = $request->description;
        //    dd($imageName);

        $site_content = new SiteContent();
        $site_content->type = $type;
        $site_content->title = $title;
        $site_content->description = $description;
        $site_content->site_id = $siteId;

        $site_content->save();


        return redirect()->back()->with('success', 'Added');
    }

    public function saveContentFoto($siteId, Request $request)
    {
        //  $title = $request->title;
        $type = $request->type;
        $imageName = $request->image_path?->getClientOriginalName();
        //    dd($imageName);

        $siteImg = new SiteContent();
        $siteImg->type = $type;
        $siteImg->site_id = $siteId;
        $siteImg->image_path = '/img/cases/' . $imageName;

        $siteImg->save();
        $request->image_path->move(public_path('/img/cases'), $imageName);

        return redirect()->back()->with('success', 'Added');
    }

    public function fetchProjects($token)
    {
        if ($token == '$2a$12$DCzuImtVKVssJ5s0aRm3etzp4lh8Mx5vVxWYqlWA28rVKnQ19V2')
                return Site::select('sites.*')->join('projects', 'sites.project_id', '=', 'projects.id')->where('projects.status', 'Afgerond')->orderBy('date','DESC')->get()->toArray();
        return false;

    }
    public function fetchLastContent($token)
    {
        if ($token == '$2a$12$zStu3Agnw9XzxJeROWRheG6vkwkzKC1OAxNd3zHpChwDpgfFdp5W') {
            $fetchContent = SiteContent::select('site_contents.*')->join('sites', 'site_contents.site_id', '=', 'sites.id')->orderBy('created_at','ASC')->get()->toArray();
            return response()->json($fetchContent);

        }
        return false;

    }
    public function fetchWebsites($token)
    {
        if ($token == '$2a$12$A5ltTTJ1zlkPbpXlRamtCef4dPIXsfWgUvo0d14THa1U8DLkZiT7S')
            return Site::select('sites.*')->join('projects', 'sites.project_id', '=', 'projects.id')->join('customers', 'sites.project_id', '=', 'customers.id')->where('projects.status', 'Afgerond')->where('sites.type','Website')->orderBy('date','desc')->take(15)->get()->toArray();
        return false;

    }
    public function fetchWebwinkels($token)
    {
        if ($token == '$2a$12$Q6muEOEivfSWzw6ghskuEUO1qKchThg7vInh54w9HXH8KO2IzzeG')
            return Site::select('sites.*')->join('projects', 'sites.project_id', '=', 'projects.id')->join('customers', 'sites.project_id', '=', 'customers.id')->where('projects.status', 'Afgerond')->where('sites.type','Webwinkel')->orderBy('date','desc')->take(15)->get()->toArray();
        return false;

    }
    public function fetchSoftwares($token)
    {
        if ($token == '$2a$12$D5tfLemUnK9Nz05qjkje8undAbv5ccj5lVGnwLjptuyXvTD8eC')
            return Site::select('sites.*')->join('projects', 'sites.project_id', '=', 'projects.id')->join('customers', 'sites.project_id', '=', 'customers.id')->where('projects.status', 'Afgerond')->where('sites.type','Software')->orderBy('date','desc')->take(15)->get()->toArray();
        return false;

    }

    ////////////////////////////////////////////


    public function testFetch()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ob.customerr.nl/api/site-crm-fetch/$2a$12$DCzuImtVKVssJ5s0aRm3etzp4lh8Mx5vVxWYqlWA28rVKnQ19V2',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $projects = $response;

        return view('crm-site.test-fetch', compact('projects'));
    }

    public function fetchProjectsContent($token, $siteN)
    {
        if ($token == '$2a$12$zStu3Agnw9XzxJeROWRheG6vkwkzKC1OAxNd3zHpChwDpgfFdp5W') {
            $fetchContent = SiteContent::select('site_contents.*')->join('sites', 'site_contents.site_id', '=', 'sites.id')->where('sites.slug', $siteN)->orderBy('sort')->get()->toArray();
            return response()->json($fetchContent);

        }
        return false;

    }

    public function testFetchContent($siteN)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ob.customerr.nl/api/site-crm-fetch-content/$2a$12$zStu3Agnw9XzxJeROWRheG6vkwkzKC1OAxNd3zHpChwDpgfFdp5W/' . $siteN,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $projectsContent = $response;

        return view('crm-site.test-fetch-content', compact('projectsContent'));
    }
}
