<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\ContactUs;
use App\Models\Product;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

class HomeController extends Controller
{
    public function index()
    {
        SEOTools::setTitle('Home');
        SEOTools::setDescription('This is my page description');
        SEOTools::opengraph()->setUrl('http://current.url.com');
        SEOTools::setCanonical('https://codecasts.com.br/lesson');
        SEOTools::opengraph()->addProperty('type', 'articles');
        SEOTools::twitter()->setSite('@LuizVinicius73');
        SEOTools::jsonLd()->addImage('https://codecasts.com.br/img/logo.jpg');

        $indexTopBanners = Banner::where('type','index_top')->where('is_active',1)->orderBy('priority')->get();
        $indexBottomBanners = Banner::where('type','index_bottom')->where('is_active',1)->orderBy('priority')->get();
        $sliders = Banner::where('type','slider')->where('is_active',1)->orderBy('priority')->get();
        $products = Product::where('is_active',1)->with(['variations.attribute','attributes.attribute','category.parent','tags','images','rates'])->get()->take(5);

        return view('home.index',compact('sliders','indexTopBanners','indexBottomBanners','products'));
    }

    public function aboutUs()
    {
        $indexBottomBanners = Banner::where('type','index_bottom')->where('is_active',1)->orderBy('priority')->get();
        return view('home.about-us.about-us',compact('indexBottomBanners'));
    }

    public function contactUs()
    {
        $settings = Setting::find(1);
        return view('home.contact-us.contact-us',compact('settings'));
    }

    public function contactUsForm(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email',
            'subject'=>'required',
            'text'=>'required',
            'g-recaptcha-response' => [new GoogleReCaptchaV3ValidationRule('contact_us')]
        ]);

        ContactUs::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'subject'=>$request->subject,
            'text'=>$request->text
        ]);

        alert()->success('باتشکر','پیام شما به موفقیت ثبت شد');
        return redirect()->back();
    }
}
