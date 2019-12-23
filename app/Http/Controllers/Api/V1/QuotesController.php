<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Quote\QuoteResource;
use App\Models\Member;
use App\Models\Post;
use Illuminate\Http\Request;

class QuotesController extends Controller
{
    private $quotes = [
        'You can totally do this.',
        'Impossible is for the unwilling.',
        'No pressure, no diamonds.',
        'Stay foolish to stay sane.',
        'When nothing goes right, go left.',
        'Try Again. Fail again. Fail better.',
        'Don’t tell people your plans. Show them your results.',
        'I can and I will.',
        'Take the risk or lose the chance.',
        'Prove them wrong.',
        'No guts, no story.',
        'My life is my message.',
        'Screw it, let’s do it.',
        'Boldness be my friend.',
        'Keep going. Be all in.',
        'My life is my argument.',
        'Dream big. Pray bigger.',
        'Leave no stone unturned. ',
        'Fight till the last gasp.',
        'Stay hungry. Stay foolish.',
        'Broken crayons still color.',
        'And so the adventure begins.',
        'If you want it, work for it.',
        'You can if you think you can.',
        'Whatever you are, be a good one.',
        'Grow through what you go through.',
        'Do it with passion or not at all.',
        'She believed she could, so she did.',
        'The past does not equal the future.',
        'Good things happen to those who hustle.',
        'At the end of hardship comes happiness.',
        'Don’t dream your life, live your dream.',
        'If it matters to you, you’ll find a way.',
        'Forget about style; worry about results.',
        'Whatever you do, do with all your might.',
        'Dream without fear. Love without limits.',
        'Every noble work is at first impossible.',
        'If you’re going through hell, keep going.',
        'You can do anything you set your mind to.',
        'We are twice armed if we fight with faith.',
        'The wisest mind has something yet to learn.',
        'Open your mind. Get up off the couch. Move.',
        'Be faithful to that which exists within yourself.',
        'Persistence guarantees that results are inevitable.',
        'In life you need either inspiration or desperation.',
        'I would rather die on my feet than live on my knees.',
        'The true success is the person who invented himself.',
        'Let him that would move the world first move himself.',
        'Go forth on your path, as it exists only through your walking.',
        'We can do anything we want to if we stick to it long enough.',
        'It does not matter how slowly you go as long as you do not stop.',
        'It is better to live one day as a lion, than a thousand days as a lamb.',
    ];

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRandom()
    {
        $randIndex = array_rand($this->quotes);

        return response()->json(['data' => $this->quotes[$randIndex]]);
    }
}
