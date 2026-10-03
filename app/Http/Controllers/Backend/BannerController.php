<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (auth()->user()->role_id != 1) {
                return redirect('/')->withErrors([
                    'email' => 'You do not have administrative privileges.'
                ]);
            }

            return $next($request);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | BANNER INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $banners = Banner::orderBy('section')
            ->orderBy('sort_order')
            ->paginate(15);

        return view(
            'admin.pages.banners.index',
            compact('banners')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.pages.banners.create');
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'title'       => 'nullable|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string',

            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:43008',

            'link'        => 'nullable|url|max:255',

            'button_text' => 'nullable|string|max:50',

            'section'     => 'required|in:hero,section_banner,footer,about,services,gallery,contact',

            'status'      => 'nullable|boolean',

            'sort_order'  => 'nullable|integer|min:0',

        ]);


        if ($request->hasFile('image')) {

            $validated['image'] =
                $this->storeImage(
                    $request->file('image')
                );

        }


        $validated['status'] =
            $request->has('status') ? 1 : 0;

        $validated['sort_order'] =
            $request->input('sort_order', 0);


        Banner::create($validated);


        return redirect()
            ->route('admin.banners.index')
            ->with(
                'success',
                'Banner created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Banner $banner)
    {
        return view(
            'admin.pages.banners.edit',
            compact('banner')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Banner $banner
    ) {

        $validated = $request->validate([

            'title'       => 'nullable|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string',

            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:43008',

            'link'        => 'nullable|url|max:255',

            'button_text' => 'nullable|string|max:50',

            'section'     => 'required|in:hero,section_banner,footer,about,services,gallery,contact',

            'status'      => 'nullable|boolean',

            'sort_order'  => 'nullable|integer|min:0',

        ]);


        /*
        |--------------------------------------------------------------------------
        | IMAGE UPDATE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $banner->deleteImageFile();

            $validated['image'] =
                $this->storeImage(
                    $request->file('image')
                );

        } elseif ($request->boolean('remove_image')) {

            $banner->deleteImageFile();

            $validated['image'] = null;

        } else {

            $validated['image'] =
                $banner->image;

        }


        $validated['status'] =
            $request->has('status') ? 1 : 0;

        $validated['sort_order'] =
            $request->input('sort_order', 0);


        $banner->update($validated);


        return redirect()
            ->route('admin.banners.index')
            ->with(
                'success',
                'Banner updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Banner $banner)
    {
        $banner->deleteImageFile();

        $banner->delete();


        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully.'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH BANNERS
    |--------------------------------------------------------------------------
    |
    | This method searches banners directly from the database.
    | It searches both:
    |
    | 1. title
    | 2. section
    |
    */

    public function search(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | GET SEARCH VALUE
        |--------------------------------------------------------------------------
        */

        $search = trim(
            $request->input('q', '')
        );


        /*
        |--------------------------------------------------------------------------
        | DATABASE SEARCH
        |--------------------------------------------------------------------------
        */

        $banners = Banner::query()

            ->where(function ($query) use ($search) {

                $query
                    ->where(
                        'title',
                        'LIKE',
                        '%' . $search . '%'
                    )

                    ->orWhere(
                        'section',
                        'LIKE',
                        '%' . $search . '%'
                    );

            })

            ->orderBy('section')
            ->orderBy('sort_order')

            ->get();


        /*
        |--------------------------------------------------------------------------
        | RETURN JSON FOR AJAX SEARCH
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'banners' => $banners
                ->map(function ($banner) {

                    return [

                        'id' =>
                            $banner->id,

                        'image_url' =>
                            $banner->image_url,

                        'title' =>
                            $banner->title,

                        'section' =>
                            $banner->section,

                        'status' =>
                            $banner->status,

                        'sort_order' =>
                            $banner->sort_order,

                        'edit_url' =>
                            route(
                                'admin.banners.edit',
                                $banner->id
                            ),

                        'delete_url' =>
                            route(
                                'admin.banners.destroy',
                                $banner->id
                            ),

                    ];

                })

                ->values(),

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | STORE IMAGE
    |--------------------------------------------------------------------------
    */

    private function storeImage($file): string
    {
        $destination =
            public_path('banners');


        if (!File::exists($destination)) {

            File::makeDirectory(
                $destination,
                0755,
                true
            );

        }


        $filename =
            time()
            . '_'
            . Str::random(8)
            . '.'
            . $file->getClientOriginalExtension();


        $file->move(
            $destination,
            $filename
        );


        return 'banners/' . $filename;
    }
}