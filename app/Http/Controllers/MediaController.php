<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::with('client', 'contract');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $media = $query->latest()->paginate(18);
        $clients = Client::where('status', 'activo')->orderBy('business_name')->get();

        return view('media.index', compact('media', 'clients'));
    }

    public function create()
    {
        $clients = Client::where('status', 'activo')->orderBy('business_name')->get();
        $contracts = Contract::where('status', 'activo')->with('client')->get();
        return view('media.create', compact('clients', 'contracts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:imagen,arte_animado,video',
            'file' => 'required|file|max:102400|mimes:jpg,jpeg,png,gif,webp,mp4,avi,mov',
            'client_id' => 'nullable|exists:clients,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'notes' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');

        $data = [
            'name' => $request->name,
            'type' => $request->type,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'client_id' => $request->client_id,
            'contract_id' => $request->contract_id,
            'status' => 'activo',
            'notes' => $request->notes,
        ];

        if (str_starts_with($file->getMimeType(), 'image/')) {
            $imageSize = @getimagesize($file->getRealPath());
            if ($imageSize) {
                $data['width'] = $imageSize[0];
                $data['height'] = $imageSize[1];
            }
            $data['thumbnail_path'] = $path;
        }

        Media::create($data);

        return redirect()->route('media.index')->with('success', 'Archivo subido correctamente.');
    }

    public function show(Media $medium)
    {
        $medium->load('client', 'contract');
        return view('media.show', ['media' => $medium]);
    }

    public function edit(Media $medium)
    {
        $clients = Client::where('status', 'activo')->orderBy('business_name')->get();
        $contracts = Contract::where('status', 'activo')->with('client')->get();
        return view('media.edit', ['media' => $medium, 'clients' => $clients, 'contracts' => $contracts]);
    }

    public function update(Request $request, Media $medium)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:imagen,arte_animado,video',
            'client_id' => 'nullable|exists:clients,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'status' => 'required|in:activo,inactivo,en_revision',
            'notes' => 'nullable|string',
        ]);

        $medium->update($request->only('name', 'type', 'client_id', 'contract_id', 'status', 'notes'));

        return redirect()->route('media.show', $medium)->with('success', 'Medio actualizado correctamente.');
    }

    public function destroy(Media $medium)
    {
        if ($medium->file_path) {
            Storage::disk('public')->delete($medium->file_path);
        }
        if ($medium->thumbnail_path && $medium->thumbnail_path !== $medium->file_path) {
            Storage::disk('public')->delete($medium->thumbnail_path);
        }
        $medium->delete();

        return redirect()->route('media.index')->with('success', 'Archivo eliminado correctamente.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400|mimes:jpg,jpeg,png,gif,webp,mp4,avi,mov',
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');
        $mime = $file->getMimeType();
        $type = str_starts_with($mime, 'image/') ? 'imagen' : 'video';

        $data = [
            'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'type' => $type,
            'file_path' => $path,
            'mime_type' => $mime,
            'file_size' => $file->getSize(),
            'status' => 'activo',
        ];

        if (str_starts_with($mime, 'image/')) {
            $imageSize = @getimagesize($file->getRealPath());
            if ($imageSize) {
                $data['width'] = $imageSize[0];
                $data['height'] = $imageSize[1];
            }
            $data['thumbnail_path'] = $path;
        }

        $media = Media::create($data);

        return response()->json(['success' => true, 'media' => $media]);
    }
}
