<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Amount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\Enums\UserIdEnum;


class BankaController extends Controller
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
    
        $amounts = DB::table('amounts')
            ->join('users', 'users.id', '=', 'amounts.user_id')
            ->select('amounts.*', 'users.name')
            ->where('users.id', '=', UserIdEnum::banka)
            ->whereYear('date', '=', date('Y'))
            ->get();
            
        $datefrombanka = date('Y-01-01');
        $datetobanka = date('Y-12-31');
        if (session()->has('datefrombanka' )) 
        {
            $datefrombanka=session()->get ('datefrombanka');
            $datetobanka=session()->get ('datetobanka');
                    }
        else {
            $datefrombanka = session()->put ('datefrombanka', date('Y-01-01'));
            $datetobanka = session()->put ('datetobanka', date('Y-12-31'));
        }
        
        return view('rns.banka', compact ('amounts','datetobanka','datefrombanka'));
    }

    public function createAmount(Request $request) 
    {
        
        $amount=new Amount();
        $amount->amount=$request->amount;
        $amount->user_id=UserIdEnum::banka;
        $amount->type=$request->type;
        $amount->date=$request->date;
        $amount->description=$request->description;
        
        
        
        if (is_numeric($request->amount)){
         \Session::flash('poruka', 'Uspješno ste unijeli iznos '.$request->amount);
         // \Session::flash('message', 'Uspjeh!');
        
        $amount->save();        }

        else {
            \Session::flash('poruka', 'Unijeli ste pogrešan format. Decimale se unose sa tačkom.');
            \Session::flash('css_klasa_poruke', 'alert-danger');
            \Session::flash('message', 'Greška!');


        }

        return redirect('/banka');
    }

    public function editAmount($id) 
    {
        
        $edit = Amount::find($id);
        $users = User::get();
        
        //$blog = Blog::where('title', 'blog2');
        //dd($blog);
        return view('rns.editbanka', compact('edit','users'));

    }

    
    

    public function updateAmount($id, Request $request) 
    {
        $input = $request->all();
        // Amount::find($id)->update($input);

        $amount= Amount::find($id);
        $amount->amount=$request->amount;
        $amount->user_id=UserIdEnum::banka;
        $amount->type=$request->type;
        $amount->date=$request->date;
        $amount->description=$request->description;
        
        if (is_numeric($request->amount)){
         \Session::flash('poruka', 'Uspješno ste unijeli iznos '.$request->amount);

        $amount->save();        
     }
         else {
            \Session::flash('poruka', 'Unijeli ste pogrešan format. Decimale se unose sa tačkom.');
            \Session::flash('css_klasa_poruke', 'alert-danger');
            \Session::flash('message', 'Greška!');
        }

        return redirect('banka');
    }


    public function searchDates(Request $request)
    {
        // $users = User::where('id', '=', 100)->get();
        $amounts = DB::table('amounts')
            ->join('users', 'users.id', '=', 'amounts.user_id')
            ->select('amounts.*', 'users.name')
            ->where('users.id', '=', UserIdEnum::banka)
            ->whereBetween('date', [$request->datefrombanka, $request->datetobanka])
            ->get();
        
        $request->session()->put ('datefrombanka', $request->datefrombanka);
        $request->session()->put ('datetobanka', $request->datetobanka);
        
        $datefrombanka=$request->session()->get ('datefrombanka', $request->datefrombanka);
        $datetobanka=$request->session()->get ('datetobanka', $request->datetobanka);

        return view('rns.banka', compact('amounts', 'datefrombanka', 'datetobanka'));
    }


    public function reportbankAmount()
    {
       
        $amounts = DB::table('amounts')
                ->join('users', 'users.id', '=', 'amounts.user_id')
                ->select(DB::raw('YEAR(date) as year,SUM(CASE WHEN type="Prihod" THEN amount ELSE 0 end) AS Prihod,
                    SUM(CASE WHEN type="Rashod" THEN amount ELSE 0 end) AS Rashod'))
                ->where('users.id', '=', UserIdEnum::banka)
                ->groupBy('year')
                ->orderBy('year','DSC')
                ->get();
    
     
        
        return view('rns.bankreport', compact('amounts'));
    }


    public function bankreportdetailsAmount($id)
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
                ->where('users.id', '=', UserIdEnum::banka)
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
                ->where('users.id', '=', UserIdEnum::banka)
                ->where(function($query) use($id){
                            $query->whereRaw(DB::raw('YEAR(date)='.$id));
                    })
                ->groupBy('user')
                ->orderBy('user','ASC')
                ->get();
                // return $korisnici;
        return view('rns.bankreportdetails', compact('mjesecni_array', 'mjesecni', 'korisnici'));
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
