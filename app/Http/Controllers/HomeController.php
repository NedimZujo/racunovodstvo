<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Amount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('id', '!=', 100)->get();
        $amounts = DB::table('amounts')
            ->join('users', 'users.id', '=', 'amounts.user_id')
            ->select('amounts.*', 'users.name')
            ->where('users.id', '!=', 100)
            ->whereYear('date', '=', date('Y'))
            ->get();
        $datefrom = date('Y-01-01');
        $dateto = date('Y-12-31');
        if (session()->has('datefrom' )) 
        {
            $datefrom=session()->get ('datefrom');
            $dateto=session()->get ('dateto');
                    }
        else {
            $datefrom = session()->put ('datefrom', date('Y-01-01'));
            $dateto = session()->put ('dateto', date('Y-12-31'));
        }
        
        return view('rns.index', compact ('users', 'amounts','dateto','datefrom'));
    }

    public function createAmount(Request $request) 
    {
        $input = $request->all();
        

        if (is_numeric($request->amount)){
         \Session::flash('poruka', 'Uspješno ste unijeli iznos '.$request->amount);
         // \Session::flash('message', 'Uspjeh!');
        
         Amount::create($input);
        }

        else {
            \Session::flash('poruka', 'Unijeli ste pogrešan format. Decimale se unose sa tačkom.');
            \Session::flash('css_klasa_poruke', 'alert-danger');
            \Session::flash('message', 'Greška!');


        }

        return redirect('/home');
    }

    public function editAmount($id) 
    {
        
        $edit = Amount::find($id);
        $users = User::where('id', '!=', 100)->get();
        
        //$blog = Blog::where('title', 'blog2');
        //dd($blog);
        return view('rns.edit', compact('edit','users'));

    }

    
    

    public function updateAmount($id, Request $request) 
    {
        $input = $request->all();

         if (is_numeric($request->amount)){
         \Session::flash('poruka', 'Uspješno ste unijeli iznos '.$request->amount);

        Amount::find($id)->update($input);

        }

        else {
            \Session::flash('poruka', 'Unijeli ste pogrešan format. Decimale se unose sa tačkom.');
            \Session::flash('css_klasa_poruke', 'alert-danger');
            \Session::flash('message', 'Greška!');


        }
        
        return redirect('home');
    }


    public function searchDates(Request $request)
    {
        $users = User::get();
        $amounts = DB::table('amounts')
            ->join('users', 'users.id', '=', 'amounts.user_id')
            ->select('amounts.*', 'users.name')
            ->whereBetween('date', [$request->datefrom, $request->dateto])
            ->get();
        
        $request->session()->put ('datefrom', $request->datefrom);
        $request->session()->put ('dateto', $request->dateto);
        
        $datefrom=$request->session()->get ('datefrom', $request->datefrom);
        $dateto=$request->session()->get ('dateto', $request->dateto);

        return view('rns.index', compact('amounts','users', 'datefrom', 'dateto'));
    }


    public function reportAmount()
    {
       
        $amounts = DB::table('amounts')
                ->join('users', 'users.id', '=', 'amounts.user_id')
                ->select(DB::raw('YEAR(date) as year,SUM(CASE WHEN type="Prihod" THEN amount ELSE 0 end) AS Prihod,
                    SUM(CASE WHEN type="Rashod" THEN amount ELSE 0 end) AS Rashod'))
                ->where('users.id', '!=', 100)
                ->groupBy('year')
                ->orderBy('year','DSC')
                ->get();
    
     
        
        return view('rns.report', compact('amounts'));
    }


    public function reportdetailsAmount($id)
    {


        // $mjesecni = DB::table('amounts')
        //         ->select(DB::raw('MONTH(date) as month,SUM(CASE WHEN type="Prihod" THEN amount ELSE 0 end) AS Prihod,
        //             SUM(CASE WHEN type="Rashod" THEN amount ELSE 0 end) AS Rashod'))
        //         ->where(function($query) use($id){
                            // $query->whereRaw(DB::raw('YEAR(date)='.$id));
                    // })
        //         ->groupBy('month')
        //         ->orderBy('month','ASC')
        //         ->get();


        $mjesecni = Amount::select(DB::raw('SUM(amount) as amount, YEAR(date) year, MONTH(date) month, type'))
                ->join('users', 'users.id', '=', 'amounts.user_id')
                ->where('users.id', '!=', 100)
                ->groupby('year','month', "type")
                ->havingRaw('year = '.$id)
                ->orderBy('month','ASC')
               ->get();


        $mjesecni_array=array();


       foreach($mjesecni as $single)
       {
            if($single->type=="Prihod")
                $mjesecni_array[$single->month]["prihod"] = $single->amount; 
            else if($single->type=="Rashod")
                $mjesecni_array[$single->month]["rashod"] = $single->amount;
       }


       // dd($mjesecni->toArray());
// dd($mjesecni_array);
       // die;

        
        $korisnici = DB::table('amounts')
                ->join('users', 'users.id', '=', 'amounts.user_id')
                ->select(DB::raw('name as user,SUM(CASE WHEN type="Prihod" THEN amount ELSE 0 end) AS Prihod,
                    SUM(CASE WHEN type="Rashod" THEN amount ELSE 0 end) AS Rashod'))
                ->where('users.id', '!=', 100)
                ->where(function($query) use($id){
                            $query->whereRaw(DB::raw('YEAR(date)='.$id));
                    })
                ->groupBy('user')
                ->orderBy('user','ASC')
                ->get();
                // return $korisnici;
        return view('rns.reportdetails', compact('mjesecni_array', 'mjesecni', 'korisnici'));
    }


    public function transfersredstava ()
    {

        $transfersredstava = DB::table('amounts')

                // ->join('users', 'users.id', '=', 'amounts.user_id')
                ->select(DB::raw('user_id,SUM(CASE WHEN type="Prihod" THEN amount ELSE 0 end) -
                    SUM(CASE WHEN type="Rashod" THEN amount ELSE 0 end) AS total'))
                
                ->whereRaw(DB::raw('YEAR(date)='.date('Y')))
                
                ->groupBy('user_id')
                ->orderBy('user_id','ASC')
                ->get();
                // return $transfersredstava;
        // return view('rns.reportdetails', compact('transfersredstava'));

        foreach ($transfersredstava as $transfer)
        {
        
        $amount=new Amount();
        $amount->amount=$transfer->total;
        $amount->user_id=$transfer->user_id;
        $amount->type='Rashod';
        $amount->date=date('Y-12-31');
        $amount->description='Transfer sredstava';
        $amount->transfer=1;
        $amount->save();

        $upisprihoda=new Amount();
        $upisprihoda->amount=$transfer->total;
        $upisprihoda->user_id=$transfer->user_id;
        $upisprihoda->type='Prihod';
        $upisprihoda->date=date('Y-01-01', strtotime("+1 year"));
        $upisprihoda->description='Transfer sredstava';
        $upisprihoda->transfer=1;
        $upisprihoda->save();
        }

        return redirect()->back();
    
        }



}
