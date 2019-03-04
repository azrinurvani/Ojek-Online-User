package com.mobile.azrinurvani.ojekonline.presenter;

import android.app.Activity;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import com.mobile.azrinurvani.ojekonline.MainActivity;
import com.mobile.azrinurvani.ojekonline.helper.SessionManager;
import com.mobile.azrinurvani.ojekonline.model.DataLoginRegis;
import com.mobile.azrinurvani.ojekonline.model.ResponseLoginRegister;
import com.mobile.azrinurvani.ojekonline.network.InitRetrofit;
import com.mobile.azrinurvani.ojekonline.view.LoginRegisterView;
import com.mobile.azrinurvani.ojekonline.view.activity.LoginRegisterActivity;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LoginRegisterPresenter implements ImpleLoginRegisPresenter{
    LoginRegisterView view;
    Activity activity;
    public DataLoginRegis dataLogin;
    public SessionManager session;

    public LoginRegisterPresenter(LoginRegisterView view) {
        this.view = view;
    }
    @Override
    public void registerDataUser(String nama, String phone, String email, String password, final DialogInterface dialogInterface) {
        view.showLoading();
        InitRetrofit.getInstance().registerUser(nama,phone,email,password).enqueue(new Callback<ResponseLoginRegister>() {
            @Override
            public void onResponse(Call<ResponseLoginRegister> call, Response<ResponseLoginRegister> response) {
                view.hideLoading();
                if (response.isSuccessful()) {
                    String result = response.body().getResult();
                    String msg = response.body().getMsg();
                    if (result.equals("true")) {
                       view.toastMsg(msg);
                       view.hideDialog(dialogInterface);
                    } else {
                        view.toastMsg(msg);
                    }
                }
            }

            @Override
            public void onFailure(Call<ResponseLoginRegister> call, Throwable t) {
                view.errorMessage("Gagal "+t.getLocalizedMessage());
                view.hideLoading();
            }
        });
    }

//    @Override
//    public void loginUser(String device, String email, String password, final DialogInterface dialogInterface) {
//        view.showLoading();
//        InitRetrofit.getInstance().registerLogin(device,email,password).enqueue(new Callback<ResponseLoginRegister>() {
//            @Override
//            public void onResponse(Call<ResponseLoginRegister> call, Response<ResponseLoginRegister> response) {
//                view.hideLoading();
//                if (response.isSuccessful()) {
//                    String result = response.body().getResult();
//                    String msg = response.body().getMsg();
//                    if (result.equals("true")) {
//                        view.toastMsg(msg);
//                        view.hideDialog(dialogInterface);
//                        String token = response.body().getToken();
//                        dataLogin = response.body().getData();
//                        session = new SessionManager(activity);
//                        session.createLoginSession(token);
//                        session.setIduser(dataLogin.getIdUser());
////                        activity.startActivity(new Intent(activity,MainActivity.class));
////                        activity.finish();
//                    } else {
//                        view.toastMsg(msg);
//                    }
//                }
//            }
//
//            @Override
//            public void onFailure(Call<ResponseLoginRegister> call, Throwable t) {
//                view.hideLoading();
//                view.errorMessage(t.getLocalizedMessage());
//            }
//        });
//    }
}
