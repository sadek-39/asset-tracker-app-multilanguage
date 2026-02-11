package com.sadekinflack.assettracker.service;

import com.sadekinflack.assettracker.entity.Asset;
import com.sadekinflack.assettracker.repository.AssetRepository;
import com.sadekinflack.assettracker.repository.UserRepository;
import com.sadekinflack.assettracker.security.SecurityUtils;
import org.springframework.stereotype.Service;
import java.util.List;

@Service
public class AssetService {
    private final AssetRepository repository;
    private final UserRepository userRepository;

    public AssetService(AssetRepository repository, UserRepository userRepository) {
        this.repository = repository;
        this.userRepository = userRepository;
    }

    public List<Asset> getAllAssets() {
        return repository.findByUserId(SecurityUtils.getCurrentUserId(userRepository));
    }

    public Asset createAsset(Asset asset) {
        asset.setUserId(SecurityUtils.getCurrentUserId(userRepository));
        return repository.save(asset);
    }
}
