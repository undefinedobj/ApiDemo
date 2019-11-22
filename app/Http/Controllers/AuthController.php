<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterFormRequest;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * 注册接口
     *
     * @param RegisterFormRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function register(RegisterFormRequest $request)
    {
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();
        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    /**
     * 登录接口
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ( ! $token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
            ], 400);
        }
        return response(['status' => 'success'])
            ->header('Authorization', $token);
    }

    /**
     * 获取当前登录用户数据
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);

        // 1.未使用 Fractal 扩展包 transformer
        /*return response([
            'status' => 'success',
            'data' => $user
        ]);*/

        // 2.使用 Fractal 扩展包 transformer
        /*$user = fractal($user, new UserTransformer())->toArray();
        return response()->json($user);*/

        // 3.使用 Fractal 扩展包 transformer，并且添加响应状态码和响应头到响应
        /*return fractal($user, new UserTransformer())->respond(200, [
            'a-header' => 'a value',
            'another-header' => 'another value',
        ]);*/

        // 4.使用 Fractal 扩展包 transformer，并且通过回调完成更复杂功能
        return fractal($user, new UserTransformer())->respond(function(JsonResponse $response) {
            $response
                ->setStatusCode(200)
                ->header('a-header', 'a value')
                ->withHeaders([
                    'another-header' => 'another value',
                    'yet-another-header' => 'yet another value',
                ]);
        });

    }

    /**
     * 退出接口
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function logout()
    {
        JWTAuth::invalidate();
        return response([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    /**
     * 检查当前登录用户 token 是否仍然有效
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }
}
