<?php

namespace App\Http\Controllers;

use App\Models\Hello;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HelloController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $posts = Hello::latest()
      ->take(10)
      ->get();

    return Inertia::render('Hello/Index', [
      'posts' => $posts,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    $post = Hello::where('id', $id)
      ->firstOrFail();

    return Inertia::render('Hello/Show', [
      'post_hello' => $post,
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
