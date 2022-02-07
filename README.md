## Steps For testing :: 

Admin Login :: http://localhost/api/auth/login 
username : admin@gmail.com
password : password

Loged In user info :: http://localhost/api/user 
Authorization: Bearer <Token>

Invite User :: http://localhost/api/invitations
{ email : 'email@gmail.com'}

Invite post url :: http://localhost/api/auth/register/<token>
{
	'name' : 'abc',
	'user_name' : 'xyz',
	'email' : 'abc@gmail.com',
	'password' : 'xyz'
}

Activate Account using Pin :: http://localhost/api/auth/activate/account

{
	'pin' : '123456789'
}