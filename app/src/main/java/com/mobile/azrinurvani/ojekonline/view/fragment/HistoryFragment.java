package com.mobile.azrinurvani.ojekonline.view.fragment;


import android.annotation.SuppressLint;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import com.mobile.azrinurvani.ojekonline.R;
import com.mobile.azrinurvani.ojekonline.adapter.CustomRecycler;
import com.mobile.azrinurvani.ojekonline.helper.HeroHelper;
import com.mobile.azrinurvani.ojekonline.helper.SessionManager;
import com.mobile.azrinurvani.ojekonline.model.DataHistory;
import com.mobile.azrinurvani.ojekonline.model.ResponseHistory;
import com.mobile.azrinurvani.ojekonline.network.InitRetrofit;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.Unbinder;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


@SuppressLint("ValidFragment")
public class HistoryFragment extends Fragment {
    int i;//status : 1 atau 2 atau 3 atau 4
    @BindView(R.id.recyclerview)
    RecyclerView recyclerview;
    Unbinder unbinder;
    public SessionManager manager;
    public List<DataHistory> dataHistory;

    public HistoryFragment() {

    }

    public HistoryFragment(int i) {
        this.i = i;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_proses, container, false);
        unbinder = ButterKnife.bind(this, view);
        manager = new SessionManager(getActivity());
        getDataHistory();
        return view;
    }

    private void getDataHistory() {
        String token = manager.getToken();
        String device = HeroHelper.getDeviceUUID(getActivity());
        String iduser = manager.getIdUser();

            InitRetrofit.getInstance().getDataHistory(token,device,String.valueOf(i),iduser).enqueue(new Callback<ResponseHistory>() {
                @Override
                public void onResponse(Call<ResponseHistory> call, Response<ResponseHistory> response) {
                    if (response.isSuccessful()){
                        String result = response.body().getResult();
                        String msg = response.body().getMsg();
                        if (result.equals("true")){
                            Toast.makeText(getActivity(), msg, Toast.LENGTH_SHORT).show();
                            dataHistory = response.body().getData();
                            CustomRecycler adapter = new CustomRecycler(dataHistory,getActivity(),i);
                            recyclerview.setAdapter(adapter);
                            recyclerview.setLayoutManager(new LinearLayoutManager(getActivity()));
                            recyclerview.setHasFixedSize(true);

                        }
                    }
                }

                @Override
                public void onFailure(Call<ResponseHistory> call, Throwable t) {
                    Toast.makeText(getActivity(),"Gagal "+ t.getLocalizedMessage(), Toast.LENGTH_SHORT).show();
                }
            });

    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        unbinder.unbind();
    }
}
