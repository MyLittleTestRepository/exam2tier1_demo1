<?
AddEventHandler('main', 'OnBeforeEventSend', Array("EventHandler", "OnBeforeEventSend"));

class EventHandler
{

	function OnBeforeEventSend(&$arFields, &$arTemplate)
	{
		if ($arTemplate['EVENT_NAME'] == 'FEEDBACK_FORM')
		{
			if (CUser::IsAuthorized())
			{
				$id = CUser::GetID();
				$login = CUser::GetLogin();
				$name = CUser::GetFirstName();
				$author = 'Пользователь авторизован: ' . $id . ' (' . $login . ') ' . $name;
			}
			else
				$author = 'Пользователь не авторизован';

			$arFields['AUTHOR'] = $author . ', данные из формы: ' . $arFields['AUTHOR'];
			CEventLog::Log('INFO',
			               'FEEDBACK_REPLACE',
			               'main',
			               CUser::IsAuthorized() ? CUser::GetLogin() : 'guest',
			               'Замена данных в отсылаемом письме - ' . $arFields['AUTHOR']);
		}
	}
}