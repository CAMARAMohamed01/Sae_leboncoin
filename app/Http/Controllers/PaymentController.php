<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        // On configure la clé secrète Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $prixfinal = $request->input('prixfinal');
        $prixfinalcalculer = (int) ($prixfinal * 100);
        // Création de la session de paiement
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Mon Super Produit Étudiant',
                    ],
                    'unit_amount' => $prixfinalcalculer  , // 15.00€ == 1500  $prixfinalcalculer
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($session->url);
    }
    public function success(Request $request)
    {

        return redirect()->route('home');
        // Plus tard, tu pourras mettre : return view('payment.success');
    }

    public function cancel()
    {

        return back()->with('popup', 'Paiement refuser ou annulation d achat');
    }
}