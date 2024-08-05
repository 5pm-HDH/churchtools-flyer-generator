<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCustomFlyerRequest;
use App\Http\Requests\CreateTemplateFlyerRequest;
use App\Models\PreInstalledTemplateDTO;
use App\Services\TemplateDataService;
use CTApi\CTClient;
use CTApi\Models\Calendars\Appointment\Appointment;
use CTApi\Utils\CTResponseUtil;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;

class FlyerController extends Controller
{
    public function createFlyer(CreateCustomFlyerRequest $request)
    {
        $data = $request->validated();
        $fileExtension = $data["file"]->extension();
        $fileName = $data["file"]->getClientOriginalName();

        $storageFolder = $this->getStorageFolder();

        $filePath = Storage::putFileAs($storageFolder, $data['file'], $fileName.'.'.$fileExtension);
        $filePathAbsolute = Storage::path($filePath);

        $calendarId = $data['calendarId'];
        $appointmentId = $data['appointmentId'];

        $templateProcessor = new TemplateProcessor($filePathAbsolute);
        $this->fillTemplateWithData($templateProcessor, $calendarId, $appointmentId);
        $this->responseFlyerDocx($templateProcessor, "Result", $storageFolder);
        exit();
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function createFlyerFromTemplate(string $templateId, CreateTemplateFlyerRequest $request)
    {
        $data = $request->validated();
        $calendarId = $data['calendarId'];
        $appointmentId = $data['appointmentId'];

        $storageFolder = $this->getStorageFolder();
        Storage::createDirectory($storageFolder);

        $template = PreInstalledTemplateDTO::find($templateId);
        if($template == null){
            return response()->json(["error" => "Template mit der ID " . $templateId . " konnte nicht gefunden werden."], 422);
        }

        $templateProcessor = new TemplateProcessor($template->templateAbsoluteSrc);

        $this->fillTemplateWithData($templateProcessor, $calendarId, $appointmentId);
        $this->responseFlyerDocx($templateProcessor, "Result", $storageFolder);
        exit();
    }

    private function fillTemplateWithData(TemplateProcessor &$templateProcessor, string $calendarId, string $appointmentId): void
    {
        $client = CTClient::getClient();
        $response = $client->get('/api/calendars/' . $calendarId . '/appointments/' . $appointmentId);
        $data = CTResponseUtil::dataAsArray($response);

        $appointment = Appointment::createModelFromData($data["appointment"]);
        if(array_key_exists('calculatedDates', $data) && is_array($data["calculatedDates"])){
            $firstCalculation = end($data["calculatedDates"]);
            $appointment->setCalculatedStartDate($firstCalculation["startDate"]);
            $appointment->setCalculatedEndDate($firstCalculation["endDate"]);
        }

        $templateDataService = TemplateDataService::getDefault();
        $data = $templateDataService->getFormattedValuesFromAppointment($appointment);

        foreach($data as $key => $value){
            if(is_string($value)){
                $templateProcessor->setValue($key, $value);
            }
        }
    }

    private function responseFlyerDocx(TemplateProcessor $templateProcessor, string $fileName, string $storageFolder)
    {
        $storageFolderAbsolute = Storage::path($storageFolder);
        $filePathCreated = $storageFolderAbsolute . DIRECTORY_SEPARATOR . $fileName . ".docx";
        $templateProcessor->saveAs($filePathCreated);

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename='.$fileName.'.docx');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: '.filesize($filePathCreated));
        if (ob_get_length() > 0) {
            ob_clean();
        }
        flush();
        readfile($filePathCreated);
    }

    private function getStorageFolder()
    {
        return "public".DIRECTORY_SEPARATOR."flyer".DIRECTORY_SEPARATOR.date('Y-m-d-h-i-s');
    }
}
