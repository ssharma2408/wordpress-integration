<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocumentRequest;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Client;
use App\Models\Document;
use App\Models\DocumentVersion;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

use Aspose\Words\WordsApi;
use Aspose\Words\Model\Requests\ConvertDocumentRequest;

use Repsonse;

class DocumentController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documents = Document::with(['versions', 'client'])->get();

        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        abort_if(Gate::denies('document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $versions = DocumentVersion::pluck('version_number', 'id');

        $clients = Client::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.documents.create', compact('clients', 'versions'));
    }

    public function store(StoreDocumentRequest $request)
    {
        $document = Document::create($request->all());
        $document->versions()->sync($request->input('versions', []));
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $document->id]);
        }		
		
        return redirect()->route('admin.documents.index');
    }

    public function edit(Document $document)
    {
        abort_if(Gate::denies('document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $versions = DocumentVersion::pluck('version_number', 'id');		

        $clients = Client::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $document->load('versions', 'client');		
		
		/* $file = public_path($document->path);
		
		$clientId = '98972964-78d5-4aaa-9e8b-98e6d35b4d44';
		$clientSecret = '7d86185784d695853935316c4c1af071';		
		
		$wordsApi  = new WordsApi($clientId, $clientSecret);		
		
		$request = new ConvertDocumentRequest(
			$file, "html", NULL, NULL, NULL, NULL
		);
		$convert = $wordsApi->convertDocument($request);
		
		rename($convert->getPathname(), public_path('/upload/output.html')); */
		
		if( ! $document['is_converted']){
		
			$document->content = file_get_contents(public_path('/upload/output.html'));
		
		}		

        return view('admin.documents.edit', compact('clients', 'document', 'versions'));
    }

    public function update(UpdateDocumentRequest $request, Document $document)
    {
        $document->update($request->all());
        $document->versions()->sync($request->input('versions', []));
		
		$version = new DocumentVersion();
		$version->document_id = $document->id;
		$version->content = $document['content'];
		$version->version_number = $document->versions()->count() + 1;
		$version->save();

        return redirect()->route('admin.documents.index');
    }

    public function show(Document $document)
    {
        abort_if(Gate::denies('document_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $document->load('versions', 'client');

        return view('admin.documents.show', compact('document'));
    }

    public function destroy(Document $document)
    {
        abort_if(Gate::denies('document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $document->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocumentRequest $request)
    {
        $documents = Document::find(request('ids'));

        foreach ($documents as $document) {
            $document->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('document_create') && Gate::denies('document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Document();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
	
	
	public function gethtml(Request $request)	
    {
       /*  $test = $request->input('data');
		$msg = "<b>Sandeep</b>"; */
		
		
		$response = array(
          'status' => 'success',
          'msg' => '<b>Sandeep</b>',
      );
      return response()->json($response); 
		//return $msg;
    }
	
	public function download(Request $request)	
    {
		
		$doc_latestVersion = DocumentVersion::where('document_id', $request['id'])->latest('created_at')->first();		
		 
 		$file = public_path('/upload/updated.html');
		 
		file_put_contents($file, $doc_latestVersion['content']);
		
		$clientId = '98972964-78d5-4aaa-9e8b-98e6d35b4d44';
		$clientSecret = '7d86185784d695853935316c4c1af071';		
		
		$wordsApi  = new WordsApi($clientId, $clientSecret);		
		
		$request = new ConvertDocumentRequest(
			$file, "docx", NULL, NULL, NULL, NULL
		);
		$convert = $wordsApi->convertDocument($request);
		
		rename($convert->getPathname(), public_path('/upload/updated.docx'));
		
	
			  
		return response()->download(public_path('/upload/updated.docx'));
		
    }	
	
}
