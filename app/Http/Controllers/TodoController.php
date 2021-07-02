<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Todo;
use Auth;
use App\Models\User;

class TodoController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth']);
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
 public function index()
{
  // Userモデルに定義した関数を実行する．
  $todos = User::find(Auth::user()->id)->todos;
  return view('todo.index', [
    'todos' => $todos
  ]);
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('todo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     // バリデーション
  $validator = Validator::make($request->all(), [
    'todo' => 'required | max:255',
    'deadline' => 'required',
  ]);
  // バリデーション:エラー
  if ($validator->fails()) {
    return redirect()
      ->route('todo.create')
      ->withInput()
      ->withErrors($validator);
  }
  // フォームから送信されてきたデータとユーザIDをマージする
  $data = $request->merge(['user_id' => Auth::user()->id])->all();
  // create()は最初から用意されている関数
  // 戻り値は挿入されたレコードの情報
  $result = Todo::create($data);
  // ルーティング「todo.index」にリクエスト送信（一覧ページに移動）
  return redirect()->route('todo.index');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $todo = Todo::find($id);
      return view('todo.show', ['todo' => $todo]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $todo = Todo::find($id);
  return view('todo.edit', ['todo' => $todo]);
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
     // バリデーション
  $validator = Validator::make($request->all(), [
    'todo' => 'required | max:255',
    'deadline' => 'required',
  ]);
  // バリデーション:エラー
  if ($validator->fails()) {
    return redirect()
      ->route('todo.create')
      ->withInput()
      ->withErrors($validator);
  }
  // フォームから送信されてきたデータとユーザIDをマージする
  $data = $request->merge(['user_id' => Auth::user()->id])->all();
  // create()は最初から用意されている関数
  // 戻り値は挿入されたレコードの情報
  $result = Todo::create($data);
  // ルーティング「todo.index」にリクエスト送信（一覧ページに移動）
  return redirect()->route('todo.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Todo::find($id)->delete();
  return redirect()->route('todo.index');
    }
}
