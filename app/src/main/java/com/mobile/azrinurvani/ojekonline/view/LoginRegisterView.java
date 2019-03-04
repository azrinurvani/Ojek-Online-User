package com.mobile.azrinurvani.ojekonline.view;

import android.content.Context;
import android.content.DialogInterface;

public interface LoginRegisterView {
    void showLoading();
    void hideLoading();
    void hideDialog(DialogInterface dialogInterface);
    void toastMsg(String message);
    void errorMessage(String message);
    void pindahKelas(Class classTujuan);

}
