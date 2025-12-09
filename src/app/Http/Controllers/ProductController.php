<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\season;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        // 商品一覧
        $products = Product::with('seasons')->paginate(6);
        $keyword = null;

        return view('products.index', compact('products', 'keyword'));
    }

    public function show($id)
    {
        // 詳細ページ
        $product = Product::with('seasons')->findOrFail($id);

        return view('products.show', compact('product'));
    }

    public function create()
    {
        // 新規作成画面
        $seasons = Season::all();

        return view('products.create', compact('seasons'));
    }

    public function store(ProductRequest $request)
    {
        // 新規保存処理
        $validated = $request->validated(
            [
                'name'        => 'required|string|max:255',
                'price'       => 'required|integer|between:0,10000',
                'image'       => 'required|image|mimes:png,jpeg',
                'description' => 'required|string|max:120',
                'season_ids'  => 'required|array',
                'season_ids.*'=> 'exists:seasons,id',
            ],
            [
            // 商品名
                'name.required'        => '商品名を入力してください',

            // 値段
                'price.required'       => '値段を入力してください',
                'price.integer'        => '数値で入力してください',
                'price.between'        => '0〜10000円以内で入力してください',

            // 画像
                'image.required'       => '画像を登録してください',
                'image.image'          => '画像を登録してください',
                'image.mimes'          => '「.png」または「.jpeg」形式でアップロードしてください',

            // 季節
                'season_ids.required'  => '季節を選択してください',

            // 商品説明
                'description.required' => '商品説明を入力してください',
                'description.max'      => '120文字以内で入力してください',
        ]
        );

        $imageFile = $request->file('image');
        $filename  = $imageFile->getClientOriginalName();
        $imageFile->storeAs('fruits-img', $filename, 'public');

        $product = Product::create([
            'name'        => $validated['name'],
            'price'       => $validated['price'],
            'image'       => $filename,   // DBにはファイル名だけ
            'description' => $validated['description'],
        ]);

        $product->seasons()->sync($validated['season_ids']);

        return redirect()->route('products.thanks');
    }

    public function edit($id)
    {
        // 編集画面
            $product = Product::with('seasons')->findOrFail($id);
            $seasons = Season::all();

            return view('products.edit', compact('product', 'seasons'));
    }

    public function update(ProductRequest $request, $id)
    {
        // 更新処理
        $product = Product::with('seasons')->findOrFail($id);

        $validated = $request->validated();

        $filename = $product->image;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $filename  = $imageFile->getClientOriginalName();
            $imageFile->storeAs('fruits-img', $filename, 'public');
        }

        $product->update([
            'name'        => $validated['name'],
            'price'       => $validated['price'],
            'image'       => $filename,
            'description' => $validated['description'],
        ]);

        $product->seasons()->sync($validated['season_ids']);

        return redirect()->route('products.index');

    }

    public function destroy($id)
    {
        // 削除処理
        $product = Product::findOrFail($id);

        $product->seasons()->detach();//中間テーブル削除

        $product->delete();//商品削除

        return redirect()->route('products.index');
    }

    public function search(Request $request)
    {
        // 検索処理
        $keyword = $request->input('keyword');

        $query = Product::with('seasons');

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $products = $query->paginate(6);

        $products->appends(['keyword' => $keyword]);

        return view('products.index', [
            'products' => $products,
            'keyword'  => $keyword,
            'sort'     => null,
        ]);
    }

    public function sort(Request $request)
    {
        // 並び替え処理
        $sort = $request->input('sort');

        $query = Product::with('seasons');

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');   // 価格の安い順
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');  // 価格の高い順
        }

        $products = $query->paginate(6);

        return view('products.index', [
            'products' => $products,
            'keyword'  => null,
            'sort'     => $sort,
        ]);
    }
}