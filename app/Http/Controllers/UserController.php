<?php

namespace App\Http\Controllers;

use App\User;
use App\Like;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUser;
use Image;

class UserController extends Controller
{


    public function like(request $request)
    {

		$data = array();

		$user = auth()->user();
		$likeable_id = $request->likeable_id;
		$likeable_user = User::findOrFail($likeable_id);


		if($likeable_user->likes()->where('user_id', $user->id)->delete()) {
			$data['likemsg'] = 'like';
		} else {

			$like = new Like(['likeable_id' => $likeable_id]);
			$user->likeables()->save($like);
			$data['likemsg'] = 'Unlike';
		}


        return response()->json($data);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		//get requested user
		$user = User::findOrFail($id);

		//ceck wether the user liked the current profile
		try {
			$like = $user->likes()->where('user_id', auth()->user()->id)->firstOrFail();
			$likestate = 'Unlike';
		} catch (\Exception $e) {
			$likestate = 'like';
		}

        return view('profile',['user' => $user, 'likestate' => $likestate]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		if(auth()->user()->id === $id)
		{
			return view('auth.edit',['user' => User::findOrFail($id)]);
		}
		else
		{
			return view('auth.edit',['user' => User::findOrFail(auth()->user()->id)]);
		}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request)
    {
        // Get current user
        $user = User::findOrFail(auth()->user()->id);

        // Set user values
        $user->name = $request->input('name');
		$user->first_name = $request->input('first_name');
		$user->last_name = $request->input('last_name');
		$user->relation_status = $request->input('relation_status');
		$user->address = $request->input('address');
		$user->email = $request->input('email');


        // Check if a profile image has been uploaded
        if ($request->has('profile_image')) {
            // Get image file original extension
			$imgExtension = $request->file('profile_image')->getClientOriginalExtension();
			// Intervention image cropping
			$image = Image::make($request->file('profile_image'))->resize(100, 100);
            // Make a image name based on user name and current timestamp
            $name = uniqid($user->id.'_');
            // Upload image with original extension
			$storedImage = $image->save('storage/uploads/images/'.$name.'.'.$imgExtension, 100, );
            // Set user profile image path in database to filePath
            $user->profile_image = $name.'.'.$imgExtension;
        }
        // Persist user record to database
        $user->save();

        // Return user back
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
