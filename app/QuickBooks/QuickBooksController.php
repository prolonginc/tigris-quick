<?php

namespace App\QuickBooks;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Routing\Redirector;

class QuickBooksController extends LaravelController
{
    public function connect(Client $quickbooks, ViewFactory $view_factory)
    {
        if ($quickbooks->hasValidRefreshToken()) {
            return $view_factory->make('quickbooks::disconnect')
                                ->with('company', $quickbooks->getDataService()->getCompanyInfo());
        }

        return $view_factory->make('quickbooks::connect')
                            ->with('authorization_uri', $quickbooks->authorizationUri());
    }

    public function disconnect(Redirector $redirector, Request $request, Client $quickbooks)
    {
        $quickbooks->deleteToken();
        $request->session()->flash('success', 'Disconnected from QuickBooks');

        return $redirector->back();
    }

    public function token(Redirector $redirector, Request $request, Client $quickbooks, UrlGenerator $url_generator)
    {
        $quickbooks->exchangeCodeForToken($request->get('code'), $request->get('realmId'));
        $request->session()->flash('success', 'Connected to QuickBooks');

        return $redirector->intended($url_generator->route('quickbooks.connect'));
    }
}
