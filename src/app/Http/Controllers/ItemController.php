<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $keyword = $request->query('keyword');

    // おすすめ（例：自分の出品は除外）
        $items = Item::query()
            ->when(auth()->check(), fn($q) => $q->where('user_id', '!=', auth()->id()))
            ->when($keyword, fn($q) => $q->where('name', 'like', "%{$keyword}%"))
            ->latest()
            ->get();

    // マイリスト（未ログインなら空）
        $mylistItems = collect();
        if (auth()->check()) {
            $mylistItems = auth()->user()
                ->favoriteItems()
                ->when($keyword, fn($q) => $q->where('items.name', 'like', "%{$keyword}%"))
                ->latest('favorites.created_at')
                ->get();
        }

        return view('items.index', compact('items', 'mylistItems', 'tab'));
    }

    public function show(Item $item)
    {
        $item->loadCount(['favorites','comments'])
            ->load(['categories', 'comments.user', 'favorites']);
        return view('items.show', compact('item'));
    }

    public function myItems()
    {
        $items = Item::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('items.my', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.create', compact('categories','conditions'));
    }


    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();
        $path = $request->file('image')->store('items', 'public');

    // ① まず保存
        $item = Item::create([
            'user_id'      => auth()->id(),
            'condition_id' => $validated['condition_id'],
            'name'         => $validated['name'],
            'brand'        => $validated['brand'] ?? null,
            'description'  => $validated['description'],
            'price'        => $validated['price'],
            'image_path'   => $path,
            'is_sold'      => false,
        ]);

    // ② カテゴリ保存（多対多）
        $item->categories()->sync($validated['categories']);

    // ③ 最後にリダイレクト
        return redirect()->route('profile.show', ['tab' => 'sell'])
            ->with('success', '商品を出品しました！');
    }


    public function edit(Item $item)
    {
        if ($item->user_id !== auth()->id()) {
        abort(403);
        }

        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        if ($item->user_id !== auth()->id()) {
        abort(403);
        }

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:1000',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price']);

        // 画像がアップされたら差し替え
        if ($request->hasFile('image')) {
            // 既存画像が storage 側(items/...)なら削除
            if ($item->image_path && !\Illuminate\Support\Str::startsWith($item->image_path, ['http://', 'https://', 'images/'])) {
            Storage::disk('public')->delete($item->image_path);
            }

            $path = $request->file('image')->store('items', 'public');
            $data['image_path'] = $path;
        }

        $item->update($data);

        return redirect()->route('items.my')->with('success', '商品を更新しました！');
    }

    public function destroy(Item $item)
    {
        if ($item->user_id !== auth()->id()) {
        abort(403);
        }

        // storage画像なら削除
        if ($item->image_path &&
            !\Illuminate\Support\Str::startsWith($item->image_path, ['http://','https://','images/'])) {
            Storage::disk('public')->delete($item->image_path);
        }

        $item->delete();

        return redirect()->route('items.my')
            ->with('success', '商品を削除しました！');
    }
}
