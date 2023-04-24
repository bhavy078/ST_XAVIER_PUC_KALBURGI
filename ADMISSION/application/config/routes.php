<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = "login";
$route['404_override'] = 'error_404';


/*********** USER DEFINED ROUTES *******************/

$route['loginMe'] = 'login/loginMe';
$route['dashboard'] = 'student';
$route['logout'] = 'user/logout';

$route['login-history'] = "user/loginHistoy";
$route['login-history/(:num)'] = "user/loginHistoy/$1";
$route['login-history/(:num)/(:num)'] = "user/loginHistoy/$1/$2";

//this route for forgot password
$route['forgotPassword'] = "login/forgotPassword";
$route['resetPasswordUser'] = "login/resetPasswordUser";
$route['resetPasswordConfirmUser'] = "login/resetPasswordConfirmUser";

//this route for user registration
$route['studentRegister'] = "registration/studentRegister";
$route['studentRegistrationToDB'] = "registration/studentRegistrationToDB";
$route['checkEmailExists'] = "registration/checkEmailExists";
$route['checkMobileNumberExists'] = "registration/checkMobileNumberExists";

$route['checkRegisterNumberExists'] = "registration/checkRegisterNumberExists";

$route['profile'] = "student/profile";
$route['profile/(:any)'] = "student/profile/$1";
$route['profileUpdate'] = "student/profileUpdate";
$route['profileUpdate/(:any)'] = "student/profileUpdate/$1";
$route['studentPersonalInfo'] = "student/studentPersonalInfo";
$route['studentPersonalInfoToDb'] = "student/studentPersonalInfoToDb";
$route['updateStudentBoardInfo'] = "student/updateStudentBoardInfo";


//this route for student
$route['changePassword'] = "student/changePassword";
$route['changePassword/(:any)'] = "student/changePassword/$1";

$route['viewGrievance'] = "admission/viewGrievance";
$route['viewGrievance/(:any)'] = "admission/viewGrievance/$1";

//display Student Mark
$route['getStudentMarkSheet'] = "student/getStudentMarkSheet";

$route['getStudentMarkSheet_two'] = "student/getStudentMarkSheet_two";


//display student personal info assets 
$route['getFormInformation'] = "student/getFormInformation";
$route['getStreamNamesByProgram'] = "student/getStreamNamesByProgram";

$route['saveStudentPersonalInfo'] = "student/saveStudentPersonalInfo";
$route['getStudentApplicationInfo'] = "student/getStudentApplicationInfo";
//saving exam info
$route['getStudentSchoolExamInfo'] = "student/getStudentSchoolExamInfo";
//saving info school
$route['saveStudentSchoolInfo'] = "student/saveStudentSchoolInfo";

//saving admission info 
$route['saveAdmissionInfo'] = "student/saveAdmissionInfo";

$route['studentFinalSubmission'] = "student/studentFinalSubmission";

$route['checkStudentApplicationStatus'] = "student/checkStudentApplicationStatus";
//route for personal detail
$route['personalDetails'] = "student/personalDetails";
$route['viewPersonalDetail'] = "student/viewPersonalDetail";
//route for school and admission detail

$route['schoolAndExaminationDetail'] = "student/schoolAndExaminationDetail";
$route['viewSchoolDetail'] = "student/viewSchoolDetail";
//route for combination and language detail

$route['combinationAndLanguageOpting'] = "student/combinationAndLanguageOpting";
$route['viewCombinationDetail'] = "student/viewCombinationDetail";


//print application
// $route['printApplicationForm'] = "student/printApplicationForm";
$route['printApplication'] = "student/printApplication";
$route['viewPrintApplication'] = "student/viewPrintApplication";

$route['paymentDetail'] = "student/paymentDetail";

$route['generatepdf'] = "student/generatepdf"; 

// routes for support
$route['contactUs'] = "support/contactUs"; 
$route['helpGuide'] = "support/helpGuide"; 
$route['saveContactInfo'] = "support/saveContactInfo"; 

//paytm_payment
$route['payTmPaymentProcess'] = "student/payTmPaymentProcess";
$route['payTmPaymentResponse'] = "student/payTmPaymentResponse";


//feepayment
$route['viewAdmission'] = "admission/viewAdmission";
$route['admissionFeeProcess'] = "admission/admissionFeeProcess";
$route['payTmfeePaymentResponse'] = "admission/payTmfeePaymentResponse";

$route['requestToInstallment'] = "admission/requestToInstallment";
$route['getFeePaymentInfo'] = "admission/getFeePaymentInfo";

// website grievance 
$route['saveWebsiteAdmissionGrievance'] = "student/saveWebsiteAdmissionGrievance";


//this routes for ChatBot
$route['chatBot'] = "chatbot";
$route['chatBot/chat'] = "chatbot/chat";
$route['chatBot/GET_NOTIFICATIONS'] = "chatbot/getNotifications";
$route['chatBot/GET_HOLIDAYS'] = "chatbot/getHolidays";
$route['chatBot/GET_EXAMS'] = "chatbot/getExams";