<?php

namespace App\Http\Controllers;

use App\Models\Statistic;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ApiConnectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    private function supprimer_caracteres_speciaux($texte) {
        // Remplace les caractères spéciaux par des espaces
        $texte = preg_replace('/[^A-Za-z0-9]/', ' ', $texte);
        // Enlève les espaces en début et fin de chaîne
        $texte = trim($texte);
        // Enlève les espaces en double
        $texte = preg_replace('/\s+/', ' ', $texte);
        return $texte;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $prompt = $request->prompt;
            //$prompt = $this->supprimer_caracteres_speciaux($prompt);
            //dd($prompt);
            $client = new Client();

            $response = $client->request('POST', 'https://openai80.p.rapidapi.com/chat/completions', [
                'body' => '{
                "model": "gpt-3.5-turbo",
                "messages": [
                    {
                        "role": "user",
                        "content": "'.$prompt.'"
                    }
                    ]
                }',
                'headers' => [
                    'X-RapidAPI-Host' => 'openai80.p.rapidapi.com',
                    'X-RapidAPI-Key' => '941fda43f1msh261969b320772a8p14ff81jsn42829015de4a',
                    'content-type' => 'application/json',
                ],
            ]);

            $code = $response->getStatusCode();
            $message = '';

            switch ($code){
                case 200 :
                    $results = json_decode($response->getBody());
                    $statistic = new Statistic();
                    $statistic->status = 'success';
                    $statistic->time_taken = 1;
                    $statistic->prompt = $prompt;
                    $statistic->result = $results->choices[0]->message->content;
                    $statistic->status_code = 200;
                    $statistic->save();
                    return $results;
                case 400:
                    $message = "Bad Request send";

                    return json_encode(["message" => $message],400);
                case 502:
                case 504:
                case 401:
                    $message = "Unauthorized user";
                    return json_encode(["message" => $message],401);
                case 429 :
                    $message = "Too many requests";
                    return json_encode(["message" => $message],429);
                default:
                    $message = "Unknown error";
                    return json_encode(["message" => $message]);
            }
            echo json_decode($response->getBody());
        }catch (\Exception $e){
            dd($e);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
