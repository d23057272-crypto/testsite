<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CryptoAsset;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get or create crypto assets for the user
        $cryptoAssets = $user->cryptoAssets;
        if (!$cryptoAssets) {
            $cryptoAssets = $user->createCryptoAssets();
        }
        
        // Generate addresses if needed
        $cryptoAssets->generateAddressesIfNull();
        
        // Get supported cryptocurrencies
        $supportedCryptos = CryptoAsset::getSupportedCryptos();
        
        // Get total balance in USD
        $totalBalanceUSD = $cryptoAssets->getTotalBalanceUSD();
        
        return view('user.dashboard', [
            'user' => $user,
            'cryptoAssets' => $cryptoAssets,
            'supportedCryptos' => $supportedCryptos,
            'totalBalanceUSD' => $totalBalanceUSD
        ]);
    }
}

