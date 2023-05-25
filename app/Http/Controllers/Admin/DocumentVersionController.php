<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocumentVersionRequest;
use App\Http\Requests\StoreDocumentVersionRequest;
use App\Http\Requests\UpdateDocumentVersionRequest;
use App\Models\Document;
use App\Models\DocumentVersion;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class DocumentVersionController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('document_version_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documentVersions = DocumentVersion::with(['document'])->get();

        return view('admin.documentVersions.index', compact('documentVersions'));
    }

    public function create()
    {
        abort_if(Gate::denies('document_version_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documents = Document::pluck('path', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.documentVersions.create', compact('documents'));
    }

    public function store(StoreDocumentVersionRequest $request)
    {
        $documentVersion = DocumentVersion::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $documentVersion->id]);
        }

        return redirect()->route('admin.document-versions.index');
    }

    public function edit(DocumentVersion $documentVersion)
    {
        abort_if(Gate::denies('document_version_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documents = Document::pluck('path', 'id')->prepend(trans('global.pleaseSelect'), '');

        $documentVersion->load('document');

        return view('admin.documentVersions.edit', compact('documentVersion', 'documents'));
    }

    public function update(UpdateDocumentVersionRequest $request, DocumentVersion $documentVersion)
    {
        $documentVersion->update($request->all());

        return redirect()->route('admin.document-versions.index');
    }

    public function show(DocumentVersion $documentVersion)
    {
        abort_if(Gate::denies('document_version_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documentVersion->load('document');

        return view('admin.documentVersions.show', compact('documentVersion'));
    }

    public function destroy(DocumentVersion $documentVersion)
    {
        abort_if(Gate::denies('document_version_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documentVersion->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocumentVersionRequest $request)
    {
        $documentVersions = DocumentVersion::find(request('ids'));

        foreach ($documentVersions as $documentVersion) {
            $documentVersion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('document_version_create') && Gate::denies('document_version_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DocumentVersion();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
